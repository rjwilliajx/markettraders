<?php
require_once('/var/www/WebProduction/salesforceapi/init.php');
require_once('/var/www/WebProduction/salesforceapi/functions.php');
include ("inc/data.php");
include ("inc/authnetfunction.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MTI 4.0 Charting | Activate Your Subscription</title>
<link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">


body {
	background: url(images/mainbg.png) repeat;
	padding: 0;
	margin: 0 auto;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #808080;
}


#m-container {
width: 1042px;
margin: 0 auto;
background-color:#f0eff0;
padding-bottom: 40px;
}



	#header {
	height: 49px;
	padding: 10px;
	background: url(images/charting-sales-box-bg.png) repeat-x;
	background-repeat: repeat-x;
	}
		#header-inner {
		width: 910px; margin: 0 auto;
		}
		#mti-logo {float: left;}
		#mti-link {float: right;}

	#feature-display {
	width: 910px;
	margin: 0 auto;
	background-position: top center;
	background-repeat: no-repeat;
	padding: 20px;
	}
		#feature-display-info {
		width: 550px;
		float: left;
		}
			#price {width: 150px; float: left;}
			#features {width: 380px; float: left;}

		#feature-display-info ul {
		list-style-image: url(http://www.markettraders.com/99/img/icon-blue-check.png);
		}
		#feature-display-info li {
		margin-bottom: 10px;
		}

		#feature-display-product {
		float: right;
		width: 342px;
		height: 258px;
		}

	#order-form {
	width: 936px;
	margin: 0 auto;
	background-color:#FFF;
	border: solid #DDD 1px;
	}
		#order-form-title {
		background-color:#ffce17;
		padding: 5px;
		}

		#order-form-inner {}

			#step-1, #step-2 {width: 334px; padding: 15px; float: left; height: 360px; border-right: solid #999 1px;}
			#step-3 {float: left; padding: 15px; width: 170px;}

	#extra-info {
	width: 910px;
	margin: 0 auto;
	}

	.extra-info-item {width: 50%; float: left;}
	.extra-info-item ul {
	list-style-image: url(http://www.markettraders.com/99/img/icon-bullet-arrow-gray.png);
	}
	.extra-info-item li {margin-top:-5px;}

#footer {
font-size: 11px;
color:#888;
width: 900px;
margin:30px auto;
border-top: dotted #DDD 1px;
}

.clear {clear: both;}

/* Text Styles */
.step-title {
font-family: 'Oswald', Trebuchet MS;
color:#333;
text-transform: uppercase;
font-size: 22px;
}

.step-title-sub {
font-family: 'Oswald', Trebuchet MS;
color:#888;
text-transform: uppercase;
font-size: 15px;
}

.feature-title {
font-size: 14px;
font-weight: bold;
color:#555;
}

a:link, a:visited, a:active {
color:#666;
}
a:hover {color:#888;}

.error { border: 2px solid #F00; }


</style>
</head>
<?

$connectdb = 'forextips_forum';
require_once('/var/www/WebProduction/content.markettraders.com/general/inc/dbconnect.php');

/*
field11		Text		SalesForce ID
field12		Text		Charting Contact ID

field9		Boolean		Email Opt-Out

field5		Boolean		Mastery Course
field15		Boolean		Scalping Course
field16		Boolean		Swing Trading Course
field17		Boolean		Essentials Course

field19		Text		Charting Expiration Date
*/

/*ob_start();
print_r($_POST);
$cool = ob_get_contents();
ob_end_clean();*/

define('REGUSER', 2);
define('FOREXTIPSVIP', 9);
define('MASTERYSTUDENT', 11);

//$sfid = (isset($_GET['sfid']) && preg_match('/^[a-zA-Z0-9]{15,18}$/', $_GET['sfid'])) ? $_GET['sfid'] : false;
$sfid = $_POST['sfid'];
$query = "SELECT Id, OwnerId, StageName, Credit_Card_Number__c, Credit_Card_Expiration_Date__c, CCV__c FROM Opportunity WHERE AccountId = '".$sfid."' AND Name = 'Charting Basic Download' AND StageName = 'New Deal'";
$result = $sf->query($query);
$ccnumber = $result->records[0]->fields->Credit_Card_Number__c;
$ccexpiration = $result->records[0]->fields->Credit_Card_Expiration_Date__c;
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

	$query = "SELECT Charting_WebCC_Override__c FROM Account WHERE Id = '".$sfid."'";
	$orresult = $sf->query($query);
	$override = $orresult->records[0]->fields->Charting_WebCC_Override__c == 'true';

	if ($_POST['x_response_code'] == 1 || $override)
	{
		// Attach the Charting Licenses to the account with the Activation Codes (Clients receive two)
		$activation = getActivationCode();
		$activation2 = getActivationCode();

		$license = new SObject();
		$license->fields = array('Name' => $activation, 'AccountID__c' => $sfid, 'Activation_Email__c' => $_POST['x_email']);
		$license->type = 'Charting_License__c';
		$licenseresult = $sf->create(array($license), 'Charting_License__c');

		$license2 = new SObject();
		$license2->fields = array('Name' => $activation2, 'AccountID__c' => $sfid, 'Activation_Email__c' => $_POST['x_email']);
		$license2->type = 'Charting_License__c';
		$licenseresult2 = $sf->create(array($license2), 'Charting_License__c');

		// Update the Account and the Opportunity to Pending Charge

		$opp->fields = array('StageName' => 'Closed Won', 'Charting_License__c' => $licenseresult->id, 'Charting_License2__c' => $licenseresult2->id, 'Lead_Source_Detail__c' => 'Authorized', 'Guarantee_Terms__c' => 'NOT APPLICABLE', 'Credit_Card_AuthorizeDotNet_Result__c' => $ccresult.' ('.$_POST['x_response_code'].'-'.$_POST['x_response_reason_code'].')', 'Credit_Card_AuthorizeDotNet_Result_Date__c' => $ccdate, 'Charting_Start__c' => date('Y-m-d'), 'Charting_Expiration__c' => date('Y-m-d', strtotime('+30 days')), 'Credit_Card_WebCC_Override_Code_Used__c' => ($override?'Yes':''), 'Purchase_Amount__c' => 99.00, 'Charting_Package__c' => '1 Month', 'Charting_Charge_Type__c' => 'Recurring Charge', 'Sub_Stage__c' => 'Paid', 'Payment_Plan__c' => 'In-House Paid Off', 'URL__c' => 'http://www.markettraders.com/99/');
		$oppresult = $sf->update(array($opp));
		print_r($oppresult);
		$opp2 = "SELECT Id, OwnerId, StageName, Credit_Card_Number__c, Credit_Card_Expiration_Date__c, CCV__c FROM Opportunity WHERE AccountId = '".$sfid."' AND Name = 'Charting Basic Download 2' AND StageName = 'New Deal'";
		$popp = $sf->query($opp2);
		$popp = $popp->records[0];
		$pending = new SObject();
				$pending->fields = array('Name' => 'Charting Basic Download', 'StageName' => 'Pending Charge', 'Charting_License__c' => $licenseresult->id, 'Charting_License2__c' => $licenseresult2->id, 'Lead_Source_Detail__c' => 'Authorized', 'Guarantee_Terms__c' => 'NOT APPLICABLE', 'Credit_Card_AuthorizeDotNet_Result__c' => $ccresult.' ('.$_POST['x_response_code'].'-'.$_POST['x_response_reason_code'].')', 'Credit_Card_AuthorizeDotNet_Result_Date__c' => $ccdate, 'Charting_Start__c' => date('Y-m-d', strtotime('+30 days')), 'Charting_Expiration__c' => date('Y-m-d', strtotime('+60 days')), 'Credit_Card_WebCC_Override_Code_Used__c' => ($override?'Yes':''), 'Purchase_Amount__c' => 99.00, 'Charting_Package__c' => '1 Month', 'Charting_Charge_Type__c' => 'Recurring Charge', 'Payment_Plan__c' => 'In-House Paid Off', 'Company_Is_NEXT_STEP__c' => 1);
				$pending->Id = $popp->Id;
				$pending->type = "Opportunity";
		$opp2result = $sf->update(array($pending));

		// Label the Activation Codes on the Account for email purposes
		$account = new SObject();
		$account->fields = array('Charting_Activation_Email_Trigger__c' => $activation, 'Charting_Activation_Email_Trigger2__c' => $activation2);
		$account->Id = $sfid;
		$account->type = 'Account';
		$accountresult = $sf->update(array($account));

		// Mark the Account as ready for the email to be sent
		$account = new SObject();
		$account->fields = array('ChartingBasic_Activation_Email_Marker__c' => 1, 'ForexTips_Username__c' => $_POST['username'], 'Charting_WebCC_Override__c' => 0);
		$account->Id = $sfid;
		$account->type = 'Account';
		$accountresult = $sf->update(array($account));

		$username = $_POST['username'];
		$password = $_POST['password'];
		$email = $_POST['x_email'];
		$group = FOREXTIPSVIP;

		$userid = mysql_query('SELECT userid FROM user WHERE username = "'.$username.'"', $link);
		if (mysql_num_rows($userid) == 0) // If the user doesn't exist, create them
		{
			$chars = array('A','a','B','b','C','c','D','d','E','e','F','f','G','g','H','h','I','i','J','j','K','k','L','l','M','m','N','n','O','o','P','p','Q','q','R','r','S','s','T','t','U','u','V','v','W','w','X','x','Y','y','Z','z','1','2','3','4','5','6','7','8','9','0','.','!','?',',','-','+','*','@','^');
			$salt = '';
			for ($s = 0; $s < 30; $s++)
				$salt .= $chars[rand(0, 70)];

			$passwordhash = md5(md5($password).$salt);

			// Insert into the new Forex Tips database
			mysql_query('INSERT INTO user (usergroupid, username, password, passworddate, joindate, email, options, salt)
						VALUES('.$group.', "'.$username.'", "'.$passwordhash.'", CURDATE(), '.time().', "'.$email.'", 45112663, "'.$salt.'")', $link);

			$newid = mysql_insert_id($link);
			mysql_query('INSERT INTO userfield (userid, field11, field19, field20)
						VALUES('.$newid.', "'.$sfid.'", "'.date('Y-m-d', strtotime('+14 days')).'", "1")', $link);
		}
		else // If the user does exist, update their information
		{
			$userid = mysql_fetch_row($userid);
			mysql_query('UPDATE userfield SET field11 = "'.$sfid.'", field19 = "'.date('Y-m-d', strtotime('+14 days')).'", field20 = "1" WHERE userid = '.$userid[0], $link);
		}

		$firstname = $_POST['x_first_name'];
		$lastname = $_POST['x_last_name'];
		$email = $_POST['x_email'];
		$city = $_POST['x_city'];

		// Sign user up for Moodle
/*		mysql_select_db('Moodle_DB', $link);
		mysql_query("INSERT INTO mdl_user (auth, confirmed, policyagreed, deleted, mnethostid, username, password, firstname, lastname, email, emailstop, city, country, lang, timezone, mailformat, maildigest, maildisplay, htmleditor, ajax, autosubscribe, trackforums, trustbitmask, screenreader) VALUES ('forextips', 1, 0, 0, 1, '".$username."', 'not cached', '".$firstname."', '".$lastname."', '".$email."', 0, '".$city."', 'US', 'en_utf8', 99, 1, 0, 2, 1, 1, 1, 0, 0, 0)");*/

		$n = '&';
		$username = 'u=' . $username;
		$password = 'p=' . $password;
		$activation = 'a=' . $activation;

		$firstname = 'f=' . $_POST['x_first_name'];
		$lastname = 'l=' . $_POST['x_last_name'];
		$city = 'c=' . $_POST['x_city'];
		$state = 's=' . $_POST['x_state'];
		$zip = 'z=' . $_POST['x_zip'];
		$country = 'co=' . $_POST['x_country'];
		$email = 'e=' . $_POST['x_email'];
		$phone = 'ph=' . $_POST['x_phone'];

		//sign them up for recurring billing
		$amount = '99.00';
	$name = 'Charting Monthly Subscription';
	//$length = $_POST["length"];
	//$unit = $_POST["unit"];
	$startDate = date('Y-m-d', strtotime('+30 days'));
	$cardNumber = $_POST["cardNumber"];
	$expirationDate = substr($ccexpiration, 0, 7);
	$firstName = $_POST['x_first_name'];
	$lastName = $_POST['x_last_name'];

	$loginname = '5T8q8mjTCm3';
	$transactionkey = '9jf3L296kAh9JFUS';


	//build xml to post
	$content =
			'<?xml version="1.0" encoding="utf-8"?> <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body> <ARBCreateSubscription xmlns="https://api.authorize.net/soap/v1/">' .
			"<merchantAuthentication>".
			"<name>" . $loginname . "</name>".
			"<transactionKey>" . $transactionkey . "</transactionKey>".
			"</merchantAuthentication>".
			"<subscription>".
			"<name>Subscription Information</name>".
			"<paymentSchedule>".
			"<interval>".
			"<length>12</length>".
			"<unit>months</unit>".
			"</interval>".
			"<startDate>" . $startDate . "</startDate>".
			"<totalOccurrences>9999</totalOccurrences>".
			"</paymentSchedule>".
			"<amount>99</amount>".
			"<payment>".
			"<creditCard>".
			"<cardNumber>".$ccnumber."</cardNumber>".
			"<expirationDate>".$ccexpiration."</expirationDate>".
			"</creditCard>".
			"</payment>".
			"<billTo>".
			"<firstName>".$firstName."</firstName>".
			"<lastName>".$lastName."</lastName>".
			"</billTo>".
			"</subscription>".
			'</ARBCreateSubscription> </soap:Body>
</soap:Envelope>';



	$host = 'api.authorize.net';
	$path = '/soap/v1/Service.asmx';

	//send the xml via curl
	$response = send_request_via_curl($host,$path,$content);
		list ($refId, $resultCode, $code, $text, $subscriptionId) =parse_return($response);

		$message = '<a href="https://na4.salesforce.com/'.$sfid.'">'.$_POST['x_first_name'].' '.$_POST['x_last_name'].'</a> has successfully submitted their information for charting basic and should have received the MTI 4.0 Charting Software download.';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Development Team <marketingdev@markettraders.com>' . "\r\n";
		$headers .= $_POST['test'] == 1 ? '' : 'To: eherrera@markettraders.com, ' . $owner->fields->Email . "\r\n";
		mail('jneal@nextstepfinancialholdings.com', 'Charting Download Success Alert', $message, $headers);

		$content =<<<CONTENT
		<script type="text/javascript" language="javascript">
			setTimeout("location.href='https://charting.nextstepfinancialholdings.com/downloads/mti40093.exe'",3000);
		</script>
		<div id="m-container">
	<div id="header">
    	<div id="header-inner">
            <div id="mti-logo"><img src="http://www.markettraders.com/99/img/mti-logo.png" alt="MTI - Market Traders Institute" width="144" height="48" border="0" /></div>
          <div id="mti-link"><br /><a href="http://www.markettraders.com/">www.markettraders.com</a></div>
        </div>
    </div>
    <div id="feature-display">
    	<div id="feature-display-info"><span style="font-size: 30px; color:#555; text-shadow: 1px 1px 1px #FFF;"><strong>Congratulations!</strong></span> <br />
    	  <br />
    	  <div id="price"><img src="images/icon-status-success.png" width="128" height="128" /></div>
          <div style="width: 400px; font-size: 14px; line-height: 20px; float:left;">
            Your subscription purchase is complete. You are now subscribed to MTI 4.0 Charting.<br /><br />

			<strong>What do I do next?</strong><br />
           1. Download the MTI 4.0 Charting software to your computer:
           <p align="center"><a href="http://www.markettraders.com/Software/MTI40096.exe"><img src="http://www.markettraders.com/99/img/btn-download-plain-blue.png" alt="Download MTI 4.0 Charting Subscription" width="95" height="37" border="0" /></a></p>
            2. Check your inbox. You should receive an email to your inbox momentarily, which will provide you with more instructions.<br />
            <br />
            <strong>Technical Support</strong><br />
            If you experience any technical issues, please contact our technical support staff at 877-469-8321.</p>
            </div>
            <div class="clear"></div>
      </div>
      <div id="feature-display-product">
        <img src="http://www.markettraders.com/99/img/mti-charting-product.png" alt="Mti 4.0 Charting" width="342" height="258" border="0" />      </div>
      <div class="clear"></div>
    </div>
    <div id="footer">
    <p align="center"><span style="font-size: 15px;"><strong>Market Traders Institute, Inc.</strong></span><br />
400 Colonial Center Parkway Suite #350 Lake Mary, FL 32746 | Ph: 800-866-7431</p>

Investments in foreign exchange speculation may be susceptible to sharp rises and falls as the relevant market values fluctuate, not only may investors get back less than they invested, but in the case of higher risk strategies, investors may lose the entirety of their investment. It is for this reason that when speculating in such markets it is advisable to use only risk capital. The information as presented is based on simulated trading using systems and education developed exclusively by Market Traders Institute. Simulated results do not represent actual trading. No representation is being made that any student will or is likely to achieve profits or losses similar to those shown. Past performance is not indicative of future results. Individual results vary and no representation is made that students will or are likely to achieve profits or incur losses comparable to those shown.    </p>
</div>
</div>

CONTENT;
	}
	else
	{
		if ($opp->fields->StageName != 'Pending Charge')
		{
			// Update the Account and the Opportunity to Closed Lost: Card Declined
			$opp->fields = array('StageName' => 'Closed Lost', 'Sub_Stage__c' => 'Card Declined', 'Lead_Source_Detail__c' => 'Failed Authorization', 'Guarantee_Terms__c' => 'NOT APPLICABLE', 'Credit_Card_AuthorizeDotNet_Result__c' => $ccresult.' ('.$_POST['x_response_code'].'-'.$_POST['x_response_reason_code'].')', 'Credit_Card_AuthorizeDotNet_Result_Date__c' => $ccdate);
			$result = $sf->update(array($opp));
		}

		$message = '<a href="https://na4.salesforce.com/'.$sfid.'">'.$_POST['x_first_name'].' '.$_POST['x_last_name'].'</a> has submitted their information for charting, but their credit card was declined with the following message from Authorize.net: ' . $ccresult . $ccresultcode;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Development Team <marketingdev@markettraders.com>' . "\r\n";
		$headers .= 'To: sbeecher@nextstepfinancialholdings.com, eherrera@markettraders.com, ' . $owner->fields->Email . "\r\n";
		mail('jneal@nextstepfinancialholdings.com', 'Charting Download Error Alert', $message, $headers);

		$content =<<<CONTENT
        <div id="m-container">
	<div id="header">
    	<div id="header-inner">
            <div id="mti-logo"><img src="http://www.markettraders.com/99/img/mti-logo.png" alt="MTI - Market Traders Institute" width="144" height="48" border="0" /></div>
          <div id="mti-link"><br /><a href="http://www.markettraders.com/">www.markettraders.com</a></div>
        </div>
    </div>
    <div id="feature-display">
    	<div id="feature-display-info"><span style="font-size: 30px; color:#555; text-shadow: 1px 1px 1px #FFF;"><strong>We came across an error.</strong></span> <br />
    	  <br />
    	  <div id="price"><img src="images/icon-status-error.png" width="128" height="128" /></div>
          <div style="width: 400px; font-size: 14px; line-height: 20px; float:left;">
            There has been an error in validating your credit card information.<br /><br />
            Please <a href="http://www.markettraders.com/99/">return to the form</a> and try entering your information again.<br /><br />Ensure that your billing address is the address related to the credit card.<br /><br />If you see this message again, you may need to contact your bank or credit card issuer. You may also contact your account representative at 1-866-787-8558 or <a href="mailto:cs@markettraders.com">cs@markettraders.com</a>.</span>

            </div>
            <div class="clear"></div>
      </div>
      <div id="feature-display-product">
        <img src="http://www.markettraders.com/99/img/mti-charting-product.png" alt="Mti 4.0 Charting" width="342" height="258" border="0" />      </div>
      <div class="clear"></div>
    </div>
    <div id="footer">
    <p align="center"><span style="font-size: 15px;"><strong>Market Traders Institute, Inc.</strong></span><br />
400 Colonial Center Parkway Suite #350 Lake Mary, FL 32746 | Ph: 800-866-7431</p>

Investments in foreign exchange speculation may be susceptible to sharp rises and falls as the relevant market values fluctuate, not only may investors get back less than they invested, but in the case of higher risk strategies, investors may lose the entirety of their investment. It is for this reason that when speculating in such markets it is advisable to use only risk capital. The information as presented is based on simulated trading using systems and education developed exclusively by Market Traders Institute. Simulated results do not represent actual trading. No representation is being made that any student will or is likely to achieve profits or losses similar to those shown. Past performance is not indicative of future results. Individual results vary and no representation is made that students will or are likely to achieve profits or incur losses comparable to those shown.    </p>
</div>
</div>

CONTENT;
	}
}
else // Account / Opportunity could not be found
{
		$message = $_POST['x_first_name'].' '.$_POST['x_last_name'].' has submitted their information for charting, but there was an error submitting them into SalesForce.';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Development Team <marketingdev@markettraders.com>' . "\r\n";
		$headers .= 'To: sbeecher@nextstepfinancialholdings.com, eherrera@markettraders.com, webdev@nextstepfinancialholdings.com, ' . $owner->fields->Email . "\r\n";
		mail('webdev@nextstepfinancialholdings.com', 'Charting (BASIC?) Download Error Alert', $message, $headers);

	$content =<<<CONTENT
    <div id="m-container">
	<div id="header">
    	<div id="header-inner">
            <div id="mti-logo"><img src="http://www.markettraders.com/99/img/mti-logo.png" alt="MTI - Market Traders Institute" width="144" height="48" border="0" /></div>
          <div id="mti-link"><br /><a href="http://www.markettraders.com/">www.markettraders.com</a></div>
        </div>
    </div>
    <div id="feature-display">
    	<div id="feature-display-info"><span style="font-size: 30px; color:#555; text-shadow: 1px 1px 1px #FFF;"><strong>We came across an error.</strong></span> <br />
    	  <br />
    	  <div id="price"><img src="images/icon-status-error.png" width="128" height="128" /></div>
          <div style="width: 400px; font-size: 14px; line-height: 20px; float:left;">
            					<span style="color:#666; line-height: 24px; font-size: 14px;">There was an error in processing your account information.<br />Please contact your account representative at 1-866-787-8558 or <a href="mailto:cs@markettraders.com">cs@markettraders.com</a>.</span></td>


            </div>
            <div class="clear"></div>
      </div>
      <div id="feature-display-product">
        <img src="http://www.markettraders.com/99/img/mti-charting-product.png" alt="Mti 4.0 Charting" width="342" height="258" border="0" />      </div>
      <div class="clear"></div>
    </div>
    <div id="footer">
    <p align="center"><span style="font-size: 15px;"><strong>Market Traders Institute, Inc.</strong></span><br />
400 Colonial Center Parkway Suite #350 Lake Mary, FL 32746 | Ph: 800-866-7431</p>

Investments in foreign exchange speculation may be susceptible to sharp rises and falls as the relevant market values fluctuate, not only may investors get back less than they invested, but in the case of higher risk strategies, investors may lose the entirety of their investment. It is for this reason that when speculating in such markets it is advisable to use only risk capital. The information as presented is based on simulated trading using systems and education developed exclusively by Market Traders Institute. Simulated results do not represent actual trading. No representation is being made that any student will or is likely to achieve profits or losses similar to those shown. Past performance is not indicative of future results. Individual results vary and no representation is made that students will or are likely to achieve profits or incur losses comparable to those shown.    </p>
</div>
</div>
CONTENT;
}

function getActivationCode()
{
	$letters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

	return date('YmdHis') . rand(100, 999) . $letters[rand(0, 25)] . $letters[rand(0, 25)] . $letters[rand(0, 25)] . $letters[rand(0, 25)];
}

?>
<body>
	<div id="container-inner">
		<div id="title">
			<div id="title-inner">
			<table width="832" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="658">&nbsp;</td>
	</tr>
			</table>
		  </div>
		</div>
<?=$content?>
<div style="display: none;">TEST: <?=$cool?></div>
		<div id="main-body">
			<div class="clear">
		</div>
		<div id="footer">
			<div id="footer-inner">

			</div>
		</div>
	</div>
</div>
</body>
</html>