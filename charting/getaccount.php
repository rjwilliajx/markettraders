<?php
require_once('/var/www/WebProduction/salesforceapi/init.php');
require_once('/var/www/WebProduction/salesforceapi/functions.php');

$connectdb = 'forextips_forum';
require_once('/var/www/WebProduction/content.markettraders.com/general/inc/dbconnect.php');

$response = "-1";

if ($_POST['unhash'] != '0')
{
	$ftuserresult = $_POST['unhash'] == md5($_POST['sfid'] . 'MTI' . $_POST['email']);
}
else
{
	$username = $_POST['un'];
	$password = $_POST['pw'];

	$ftuser = mysql_query('SELECT password, salt FROM user WHERE username = "'.$username.'"', $link);
	if (mysql_num_rows($ftuser) > 0)
	{
		$ftuser = mysql_fetch_assoc($ftuser);
		$ftuserresult = md5(md5($password).$ftuser['salt']) == $ftuser['password'];
	}
	else
		$ftuserresult = true;
	}

if ($userresult == 1 || $ftuserresult)
{
	$sfid = (isset($_POST['sfid']) && preg_match('/^[a-zA-Z0-9]{15,18}$/', $_POST['sfid'])) ? $_POST['sfid'] : false;

	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$country = $_POST['country'];
	$ip = $_POST['ip'];

	$pay = $_POST['pay'] == 1;

	$ccnumber = $_POST['ccnumber'];
	$ccexpiration =  '20' . substr($_POST['ccexpiration'], 2, 2) . '-' . substr($_POST['ccexpiration'], 0, 2) . '-01';
	$ccv = $_POST['ccv'];
	$ccip = $_POST['ccip'];

	$query = "SELECT Id, OwnerId FROM Account WHERE Id = '".$sfid."' AND PersonEmail = '".$email."'";
	$result = $sf->query($query);
	if (!empty($result->records)) // Account matches data entered
	{
		$result = $result->records[0];
		$response = updateAccount($sfid, $result->fields->OwnerId, $fname, $lname, $address, $city, $state, $country, $zip, $phone, $ccnumber, $ccexpiration, $ccv, $ccip, true, $pay, $ip);
	}
	else
	{
		$query = "SELECT Id, OwnerId FROM Account WHERE FirstName = '".$fname."' AND LastName = '".$lname."' AND PersonEmail = '".$email."'";
		$result = $sf->query($query);
		if (!empty($result->records)) // Account exists, but does not match SalesForce ID included
		{
			$result = $result->records[0];
			$sfid = $result->Id;
			$response = updateAccount($sfid, $result->fields->OwnerId, $fname, $lname, $address, $city, $state, $country, $zip, $phone, $ccnumber, $ccexpiration, $ccv, $ccip, true, $pay, $ip);
		}
		else // No account exists, create and convert a lead, set new Account to "New Deal" stage
		{
			$new = createLead(array('FirstName' => $fname, 'LastName' => $lname, 'Email' => $email, 'Street' => $address, 'City' => $city, 'State' => $state, 'CountryList__c' => $country, 'PostalCode' => $zip, 'Phone' => $phone, 'LeadSource' => 'Charting', 'Lead_Source_Detail__c' => '$99 Charting Package', 'RecordTypeId' => '012600000005Cwn', 'URL__c' => 'https://www.markettraders.com/charting/'));
			$lead = $sf->create(array($new), 'Lead');
			$convert = convertLead($lead->id, $sf);
			if (!$convert)
			{
			}
			else
			{
				$query = "SELECT Id, OwnerId FROM Account WHERE Id='".$convert->result->accountId."'";
				$result = $sf->query($query);
				if (!empty($result->records))
				{
					$result = $result->records[0];
					$sfid = $result->Id;
					$response = updateAccount($sfid, $result->fields->OwnerId, $fname, $lname, $address, $city, $state, $country, $zip, $phone, $ccnumber, $ccexpiration, $ccv, $ccip, false, $pay, $ip);
				}
			}
		}
	}

}
else
	$response = 2;

function updateAccount($sfid, $ownerid, $fname, $lname, $address, $city, $state, $country, $zip, $phone, $ccnumber, $ccexpiration, $ccv, $ccip, $exists, $pay, $ip)
{
	global $sf;

	$date = date('Y-m-d');

	if ($exists)
	{
		$account = new SObject();
		$account->fields = array('BillingStreet' => $address, 'BillingCity' => $city, 'BillingState' => $state, 'BillingCountry' => $country, 'BillingPostalCode' => $zip, 'Phone' => $phone);
		$account->type = 'Account';
		$account->Id = $sfid;
		$accountresult = $sf->update(array($account));

		$stagename = 'New Deal';
	}
	else // If this was a new lead coming in through the form, mark this account for follow up
	{
		$account = new SObject();
		$account->fields = array('BillingStreet' => $address, 'BillingCity' => $city, 'BillingState' => $state, 'BillingCountry' => $country, 'BillingPostalCode' => $zip, 'Phone' => $phone);
		$account->type = 'Account';
		$account->Id = $sfid;
		$accountresult = $sf->update(array($account));

		$stagename = 'New Deal';
	}

	$amount = $pay ? 99.00 : 0.00;

	$opp = new SObject();
	$opp->fields = array('Name' => 'Charting Basic Download', 'Credit_Card_Number__c' => $ccnumber, 'Credit_Card_Expiration_Date__c' => $ccexpiration, 'Name_on_Credit_Card__c' => ($fname.' '.$lname), 'Credit_Card_IP_Address__c' => $ccip, 'CCV__c' => $ccv, 'Product__c' => 'Charting Basic', 'Amount' => $amount, 'Converted_Lead_Type__c' => 'Charting', 'StageName' => $stagename, 'LeadSource' => 'Charting', 'Lead_Source_Detail__c' => 'Before Authorization', 'RecordTypeId' => '012600000005Cx2', 'AccountId' => $sfid, 'Type' => 'Charting', 'Charting_Merchant_Account_Needed__c' => 1, 'CloseDate' => $date, 'OwnerId' => $ownerid, 'Credit_Card_IP_Address__c' => $ip);
	$opp->type = 'Opportunity';
	$oppresult = $sf->create(array($opp), 'Opportunity');

	$opp = new SObject();
	$opp->fields = array('Name' => 'Charting Basic Download 2', 'Credit_Card_Number__c' => $ccnumber, 'Credit_Card_Expiration_Date__c' => $ccexpiration, 'Name_on_Credit_Card__c' => ($fname.' '.$lname), 'Credit_Card_IP_Address__c' => $ccip, 'CCV__c' => $ccv, 'Product__c' => 'Charting Basic', 'Amount' => $amount, 'Converted_Lead_Type__c' => 'Charting', 'StageName' => $stagename, 'LeadSource' => 'Charting', 'Lead_Source_Detail__c' => 'Before Authorization', 'RecordTypeId' => '012600000005Cx2', 'AccountId' => $sfid, 'Type' => 'Charting', 'Charting_Merchant_Account_Needed__c' => 1, 'CloseDate' => date('Y-m-d', strtotime('+30 days')), 'OwnerId' => $ownerid, 'Credit_Card_IP_Address__c' => $ip);
	$opp->type = 'Opportunity';
	$oppresult = $sf->create(array($opp), 'Opportunity');

	return $oppresult->success;
}

echo $response . ';' . $sfid;

?>