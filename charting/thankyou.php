<?php
require_once('/var/www/WebProduction/salesforceapi/init.php');
require_once('/var/www/WebProduction/salesforceapi/functions.php');
include ("/web/sites/marketinst/markettraders.com/99/inc/data.php");
include ("/web/sites/marketinst/markettraders.com/99/inc/authnetfunction.php");
?>

<?php
$sfid = $_POST['sfid'];

$query = "SELECT Id, OwnerId, StageName FROM Opportunity WHERE AccountId = '".$sfid."' AND Name = 'Toronto Seminar' AND StageName = 'New Deal'";
$result = $sf->query($query);
if (!empty($result->records))
{
	$opp = $result->records[0];

	$query = "SELECT Email FROM User WHERE Id = '".$opp->fields->OwnerId."'";
	$owner = $sf->query($query);
	$owner = $owner->records[0];

	if ($_POST['x_response_reason_code'] >= 243)
		$ccrchash = 243;
	else if ($_POST['x_response_reason_code'] >= 211)
		$ccrchash = 211;
	else if ($_POST['x_response_reason_code'] >= 176)
		$ccrchash = 176;
	else if ($_POST['x_response_reason_code'] >= 127)
		$ccrchash = 127;
	else if ($_POST['x_response_reason_code'] >= 101)
		$ccrchash = 101;
	else if ($_POST['x_response_reason_code'] >= 76)
		$ccrchash = 76;
	else if ($_POST['x_response_reason_code'] >= 51)
		$ccrchash = 51;
	else if ($_POST['x_response_reason_code'] >= 25)
		$ccrchash = 25;
	else
		$ccrchash = 1;

	$ccresultcode = ' <a href="http://www.authorize.net/support/merchant/Transaction_Response/Response_Reason_Codes_and_Response_Reason_Text.htm#'.$ccrchash.'">(' . $_POST['x_response_code'] . '-' . $_POST['x_response_reason_code'] . ')</a>';
	$ccresult = $_POST['x_response_reason_text'];
	$ccdate = date('Y-m-d');

	if ($_POST['x_response_code'] == 1)
	{
		if(isset($_POST['lead_source']) && $_POST['lead_source'] == 'chief_toronto_seminar')
	{
		$leadsource = 'Chiefs Toronto Seminar';
		$lsd = 'MTI Email';
	}
	else
	{
		$leadsource = 'Team Trader Planet';
		$lsd = 'Other';
	}


		// Update the Account and the Opportunity to Pending Charge
	/*	$opp->fields = array('StageName' => 'Pending Charge', 'Charting_License__c' => $licenseresult->id, 'Charting_License2__c' => $licenseresult2->id, 'Lead_Source_Detail__c' => 'Authorized', 'Guarantee_Terms__c' => 'NOT APPLICABLE', 'Credit_Card_AuthorizeDotNet_Result__c' => $ccresult.' ('.$_POST['x_response_code'].'-'.$_POST['x_response_reason_code'].')', 'Credit_Card_AuthorizeDotNet_Result_Date__c' => $ccdate, 'Charting_Start__c' => date('Y-m-d'), 'Charting_Expiration__c' => date('Y-m-d', strtotime('+14 days')), 'Credit_Card_WebCC_Override_Code_Used__c' => ($override?'Yes':''));
		$oppresult = $sf->update(array($opp));*/



$opp->fields = array('StageName' => 'Closed Won', 'Lead_Source_Detail__c' => $lsd, 'Guarantee_Terms__c' => 'NOT APPLICABLE', 'Credit_Card_AuthorizeDotNet_Result__c' => $ccresult.' ('.$_POST['x_response_code'].'-'.$_POST['x_response_reason_code'].')', 'Credit_Card_AuthorizeDotNet_Result_Date__c' => $ccdate, 'Credit_Card_WebCC_Override_Code_Used__c' => ($override?'Yes':''), 'Purchase_Amount__c' => 150.00, 'Charting_Charge_Type__c' => 'One-Time Charge', 'Sub_Stage__c' => 'Paid', 'Payment_Plan__c' => 'In-House Paid Off', 'URL__c' => 'http://www.markettraders.com/toronto/');
$oppresult = $sf->update(array($opp));
/*if($oppresult->success){
$connectdb = 'forextips_tools';
require_once('/var/www/WebProduction/content.markettraders.com/general/inc/dbconnect.php');
$checkfirst = "select * from essentailsSeminar where Email
$query = "insert into essentailsSeminar (fname, lname, phone, email) values ('".$_POST['fname']."', '".$_POST['lname']."', '".$_POST['phone']."','".$_POST['email']."')";
	$insert = mysql_query($query);
}
*/
$updatedata = array();
$updatedata['PersonOtherPhone'] = 'TorontoMarch2012';
								$sObject = new SObject();
								$sObject->Id = $sfid;
								$sObject->fields = $updatedata;
								$sObject->type = 'Account';
								$resultAcctUpdate = $sf->update(array($sObject));
								print_r($resultAcctUpdate);

		$message = '<a href="https://na4.salesforce.com/'.$sfid.'">'.$_POST['x_first_name'].' '.$_POST['x_last_name'].'</a> has successfully submitted their information for the Chiefs Toronto seminar.';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Development Team <marketingdev@markettraders.com>' . "\r\n";
		//$headers .= $_POST['test'] == 1 ? '' : 'To: sbeecher@nextstepfinancialholdings.com, mharper@markettraders.com, eherrera@markettraders.com, apotts@markettraders.com, ' . $owner->fields->Email . "\r\n";
		mail('jneal@nextstepfinancialholdings.com', 'Seminar Registration Success Alert', $message, $headers);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700' rel='stylesheet' type='text/css' />
<title>Thank You - Jared Martinez Presents: Live Forex Training Seminar</title>


</head>
<body>

	<div id="wrapper">

    	<div class="header"><img src="images/header-40-charting-software.png" alt="MTI 4.0 Charting Software" width="719" height="139" border="0"></div>
      <div class="content">
        <span style="font-family: 'Oswald', serif; font-size: 43px; color:#555; text-shadow: 1px 1px 1px #FFF;"><strong>CONGRATULATIONS!</strong></span>
        <br />
        Thank You for Registering!
        <p>You are now registered to attend Jared Martinez's, "The 10 Essentials of Forex Trading". You should receive an email to your inbox momentarily with pertinent event information. Please check your spam/junk folder if your email does not make it to your inbox.</p>
        <p style="color: #d3b514; font-weight: bold; font-style: italic;">If you do not receive an email, please call: 1.866.787.8558 or, call Norm and Nancy at 905.840.1966 or via <a href="mailto:teamtraders@rogers.com" style="text-decoration: none; color:#d3b514;"><u>teamtraders@rogers.com</u></a></p>
        <p style="text-align: center; margin-top: 30px;"><img src="http://www.markettraders.com/toronto/images/map.png" alt="Map" /></p>
        <p style="font-family: 'Open Sans Condensed', sans-serif; font-size: 40px; color: #979797; text-align: center; margin: 0;padding: 0;">DOUBLE TREE HOTEL</p>
        <p style="font-family: 'Open Sans Condensed', sans-serif; font-size: 16px; color: #979797; text-align: center; margin: 0; padding: 0;">655 Dixon Road, Toronto, ON M9W 1J3</p>
        <p>&nbsp;</p>
        </div>
      <div class="thankyou-footer">&nbsp;</div>

    </div>

</body>
</html>