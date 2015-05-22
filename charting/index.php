<?php
$fname = '';
$lname = '';
$address = '';
$city = '';
$state = '';
$zip = '';
$email = '';
$phone = '';
$country = '';
$ip = $_SERVER['REMOTE_ADDR'];

$statedisabled = 'disabled="disabled"';

$oppexists = false;

if (isset($_GET['sfid']) && preg_match('/^[a-zA-Z0-9]{15,18}$/', $_GET['sfid']))
{
	$sfid = $_GET['sfid'];
	// Search for Account
	$query = "SELECT Id, LastName, FirstName, Name, PersonEmail, Phone, BillingStreet, BillingCity, BillingState, BillingCountry, BillingPostalCode FROM Account WHERE Id = '".$sfid."'";
	$result = $sf->query($query);
	if (!empty($result->records)) // Account exists
	{
		$query = "SELECT Id FROM Opportunity WHERE AccountId = '".$sfid."' AND Name = 'Charting Download' AND (StageName = 'Pending Charge' OR StageName = 'Closed Won')";
		$oppresult = $sf->query($query);
		if (!empty($oppresult->records))
		{
			$oppexists = true;
		}
		else
		{
			$account = $result->records[0];
			$log .= 'Account Exists: ' . $account->Id . "\n";

			$fname = $account->fields->FirstName;
			$lname = $account->fields->LastName;
			$address = $account->fields->BillingStreet;
			$city = $account->fields->BillingCity;
			$state = $account->fields->BillingState;
			$zip = $account->fields->BillingPostalCode;
			$email = $account->fields->PersonEmail;
			$phone = $account->fields->Phone;
			$country = $account->fields->BillingCountry;
			$statedisabled = '';

			$connectdb = 'forextips_forum';
			require_once('/var/www/WebProduction/content.markettraders.com/general/inc/dbconnect.php');
			$ftinfo = mysql_query('SELECT username FROM user WHERE email = "'.$email.'"', $link);
			if ($ftinfo = mysql_fetch_assoc($ftinfo))
			{
				$username = $ftinfo['username'];

				$unhash = md5($sfid . 'MTI' . $email);

				$loginsection =<<<LOGIN
		<tr>
			<td class="title">Charting Username:</td><td class="title">&nbsp;</td>
		</tr><tr>
			<td class="field" style="text-align: center;"><b>{$username}</b></td>
			<td class="field">&nbsp;
				<input type="hidden" id="unhash" name="unhash" value="{$unhash}" />
				<input type="hidden" id="username" name="username" value="{$username}" />
				<input type="hidden" id="password" name="password" value="" />
			</td>
		</tr>
		<tr><td colspan="2" class="title" style="text-align: right;">* Your username is being pulled from Forex Tips</td></tr>
LOGIN;
			}
			else
			{
				$loginsection =<<<LOGIN
		<tr>
			<td class="title">Charting Username:</td><td class="title">Charting Password:</td>
		</tr><tr>
			<td class="field">
				<input id="unhash" type="hidden" name="unhash" value="0" />
				<input id="username" name="username" type="text" />
			</td>
			<td class="field"><input id="password" name="password" type="password" /></td>
		</tr>
LOGIN;
			}
		}
	}
}
else
{
	$loginsection =<<<LOGIN
		<tr>
			<td class="title">Charting Username:</td><td class="title">Charting Password:</td>
		</tr><tr>
			<td class="field"><input id="username" name="username" type="text" /></td>
			<td class="field"><input id="password" name="password" type="password" /></td>
		</tr>
LOGIN;
}
	$apiLoginId = "5T8q8mjTCm3";
	$transactionKey = "9jf3L296kAh9JFUS";

	$invoiceNumber = date('YmdHis');
	$amount = 99.00;

	$sequence	= rand(1, 1000);
	$timeStamp	= time();

	$fingerprint = hash_hmac("md5", $apiLoginId . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey);

	$authorizeNetFields =<<<FIELDS
		<input type="hidden" name="x_login" value="{$apiLoginId}" />
		<input type="hidden" name="x_fp_sequence" value="{$sequence}" />
		<input type="hidden" name="x_fp_timestamp" value="{$timeStamp}" />
		<input type="hidden" name="x_fp_hash" value="{$fingerprint}" />
		<input type="hidden" name="x_relay_response" value="TRUE" />
		<input type="hidden" name="x_relay_url" value="http://www.markettraders.com/charting/download.php" />
        <input type="hidden" name="x_amount" value="{$amount}" />
        <input type="hidden" name="x_description" value="MTI 4.0 Charting Software" />
		<input type="hidden" name="x_invoice_num" value="{$invoiceNumber}" />
		<input type="hidden" name="x_po_num" value="{$invoiceNumber}" />
		<input type="hidden" name="x_method" value="CC" />
		<input type="hidden" name="x_type" value="AUTH_CAPTURE" />
		<input type="hidden" name="x_email_customer" value="FALSE" />
		<input type="hidden" name="ip" value="{$ip}" id="ip" />
		<INPUT TYPE="HIDDEN" NAME="x_test_request" VALUE="TRUE">


FIELDS;



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Charting Sales Page</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
<style type="text/css">
body {
	background: url(images/mainbg.png) repeat;
	padding: 0;
	margin: 0 auto;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #808080;
}
#chartingcontainer {
	width: 960px;
	margin: 0 auto;
	padding: 0;
}
.chartingheader {
	background: url(images/header-subs-avail-tile.png) repeat-x;
	height: 139px;
	margin: 0 auto;
	padding: 0;
}
p {
	margin: 0;
	padding: 0;
}
.chartingcontentcontainer {
	background: url(images/charting-sales-box-bg.png) repeat-x;
	height: 390px;
	margin: 0;
	padding: 0;
}
.chartingcontentleft {
	width: 316px;
	margin: 0;
	padding: 0;
	padding-top: 29px;
	float: left;
}
.chartingcontentright {
width: 580px;
margin: 0 10px 0 0;
padding: 0;
margin-top: 29px;
float: right;
text-align: center;
position: relative;
background-color: black;
padding: 10px;
}
.chartingcontenttitle {
	background: url(images/charting-title-bar-tile.png) repeat-x;
	height: 50px;
	width: 296px;
	margin: 0;
	padding: 0;
}
.chartingcontentmid {
	background: url(images/charting-mid-content-bg.png) repeat-x;
	height: 342px;
	margin: 0;
	padding: 0;
}
.chartingcontentmidtitle{
	width: 316px;
	margin: 0;
	padding: 0;
	float: left;
}
.chartingmidvidbox {
	background: url(images/charting-page-svideo-box.png);
	height: 266px;
	width: 218px;
}
.chartingmidvidbox2 {
	background: url(images/charting-page-svideo-box2.png);
	height: 266px;
	width: 218px;
}
.chartingmidvidcontainer {
	width: 960px;
	margin: 0 auto;
	padding: 0;
	text-align: center;
	clear: both;
}
.chartingordercontent {
	margin: 0;
	padding: 0;
}
.chartingordercontenttitle{
	background: url(images/charting-ordernow-content-title.png);
	width: 960px;
	margin: 0;
	padding: 0;
	float: left;
}
.contentfooter{
	background: url(images/charting-sales-bottom-tile.png) repeat-x #fcfcfc;
	height: auto;
	width:100%;
	margin: 0;
	padding: 0;
	clear: both;
	float: left;
}

.fader{
position: absolute;
background-color:#000000;
/*background: url(images/charting-sales-box-bg.png) repeat-x;*/
width: 580px;
height: 335px;
top: 0px;
}
</style>
<script type="text/javascript" src="http://content.markettraders.com/general/js/jquery.js"></script>
<script type="text/javascript">
var jq = jQuery.noConflict();
jq(function(){
jq('.link1').click(function(e){
jq('.chartingmidvidbox').removeClass('chartingmidvidbox2');

jq(this).parent('.chartingmidvidbox').addClass('chartingmidvidbox2');

e.preventDefault();
jq('.fader').fadeIn(1000, function(){jq('.embedded').html('<object width="580" height="325"><param name="wmode" value="opaque" /><param name="movie" value="http://www.youtube.com/v/VVNSYsLNcEY?version=3&hl=en_US&start=275&autoplay=1&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/VVNSYsLNcEY?version=3&amp;hl=en_US&start=275&autoplay=1&amp;rel=0" type="application/x-shockwave-flash" width="580" height="325" wmode="opaque" allowscriptaccess="always" allowfullscreen="true"></embed></object>').siblings('.fader').fadeOut(1000);});

});
jq('.link2').click(function(e){
jq('.chartingmidvidbox').removeClass('chartingmidvidbox2');

jq(this).parent('.chartingmidvidbox').addClass('chartingmidvidbox2');
e.preventDefault();
jq('.fader').fadeIn(1000, function(){jq('.embedded').html('<object width="580" height="325"><param name="wmode" value="opaque" /><param name="movie" value="http://www.youtube.com/v/VVNSYsLNcEY?version=3&hl=en_US&start=381&autoplay=1&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/VVNSYsLNcEY?version=3&amp;hl=en_US&start=381&autoplay=1&amp;rel=0" type="application/x-shockwave-flash" width="580" height="325" wmode="opaque" allowscriptaccess="always" allowfullscreen="true"></embed></object>').siblings('.fader').fadeOut(1000);});
});

jq('.link3').click(function(e){
jq('.chartingmidvidbox').removeClass('chartingmidvidbox2');

jq(this).parent('.chartingmidvidbox').addClass('chartingmidvidbox2');
e.preventDefault();
jq('.fader').fadeIn(1000, function(){jq('.embedded').html('<object width="580" height="325"><param name="wmode" value="opaque" /><param name="movie" value="http://www.youtube.com/v/VVNSYsLNcEY?version=3&hl=en_US&start=434&autoplay=1&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/VVNSYsLNcEY?version=3&amp;hl=en_US&start=434&autoplay=1&amp;rel=0" type="application/x-shockwave-flash" width="580" height="325" wmode="opaque" allowscriptaccess="always" allowfullscreen="true"></embed></object>').siblings('.fader').fadeOut(1000);});
});

jq('.link4').click(function(e){
jq('.chartingmidvidbox').removeClass('chartingmidvidbox2');

jq(this).parent('.chartingmidvidbox').addClass('chartingmidvidbox2');
e.preventDefault();
jq('.fader').fadeIn(1000, function(){jq('.embedded').html('<object width="580" height="325"><param name="wmode" value="opaque" /><param name="movie" value="http://www.youtube.com/v/VVNSYsLNcEY?version=3&hl=en_US&start=574&autoplay=1&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/VVNSYsLNcEY?version=3&amp;hl=en_US&start=574&autoplay=1&amp;rel=0" type="application/x-shockwave-flash" width="580" height="325" wmode="opaque" allowscriptaccess="always" allowfullscreen="true"></embed></object>').siblings('.fader').fadeOut(1000);});



});
jq('.clickme').click(function(e){
e.preventDefault();
if(jq('.contentfooter').is(":visible")){
jq('html, body').delay(500).animate({
            scrollTop: jq('.holder').offset().top},'slow');
}
else{
jq('.contentfooter').slideToggle(200);
        jq('html, body').delay(500).animate({
            scrollTop: jq('.holder').offset().top},'slow');
}
//jq('.contentfooter').slideToggle(200);
});

/*
jq("scrolldown").click(function(e){
        e.preventDefault();
        jq('.contentfooter').slideToggle(200);
        jq('html, body').delay(500).animate({
            scrollTop: jq('.holder').offset().top},'slow');
//top: '+=500px'}, 'slow');
}
);


});
*/
});
</script>
</head>
<body>
	<div id="chartingcontainer">

   	  <div class="chartingheader">
       	  <div style="float: left;">
        	<img src="images/header-40-charting-software.png" alt="MTI 4.0 Charting Software" border="0" />          </div>
            <div><p style="text-align: center; padding: 35px 0 10px 0; font-family: 'Bitter', serif; font-size: 18px; color: #FFFFFF;">Subscriptions Available</p><p style="text-align: center;"><a href="#" class="clickme"><img src="images/btn-charting-get-started.png" alt="Get Started!" border="0" /></a></p>
            </div>
      </div>
      <div style="width: 700px; height: 300px; background-color:#000000;">
      </div>
      <div class="chartingcontentcontainer">
       	  <div class="chartingcontentleft">
          	<div class="chartingcontenttitle" style="float: left;">
           	  <div style="padding: 16px; float: left;"><img src="images/charting-title-arrow.png" alt="Arrow" /></div>
                <div style="padding-top: 9px; color: #313030; font-family: 'Oswald', sans-serif; font-size: 22px; text-shadow: 1px 1px #fcf0c5;">WHY MTI 4.0 CHARTING?</div>
            </div>
            <div><img src="images/charting-title-bar-right.png" alt="Title Bar Edge" /></div>
            <p style="clear: both; margin-top: 15px; text-align: right;"><img src="images/charting-content-separator.png" alt="Separator" border="0" /></p>
            <div style="float: left; margin: 0; padding: 5px 10px 6px 20px;"><img src="images/charting-icon-thumbs.png" alt="Easy to Use!" border="0" /></div><div style="margin: 0; color: #595959; font-family: 'Bitter', serif; padding: 15px; padding-bottom: 10px;">Easy-to-use platform</div>
            <p style="clear: both; text-align: right; margin: 0; padding: 0;"><img src="images/charting-content-separator.png" alt="Separator" border="0" /></p>
            <div style="float: left; margin: 0; padding: 5px 10px 6px 20px;"><img src="images/charting-icon-lightning.png" alt="Powerful!" border="0" /></div>
            <div style="margin: 0; color: #595959; font-family: 'Bitter', serif; padding: 15px; padding-bottom: 10px; padding-right: 0px;">Powerful automated trading system</div>
            <p style="clear: both; text-align: right;"><img src="images/charting-content-separator.png" alt="Separator" border="0" /></p>
            <div style="float: left; margin: 0; padding: 5px 10px 6px 20px;"><img src="images/charting-icon-clock.png" alt="Multiple Time Frame Capabilities!" border="0" /></div>
            <div style="margin: 0; color: #595959; font-family: 'Bitter', serif; padding: 15px; padding-bottom: 10px;">Multiple time frame capabilities</div>
            <p style="clear: both; text-align: right;"><img src="images/charting-content-separator.png" alt="Separator" border="0" /></p>
            <div style="float: left; margin: 0; padding: 5px 10px 6px 20px;"><img src="images/charting-icon-wifi.png" alt="Charting/Email Alerts!" border="0" /></div>
            <div style="margin: 0; color: #595959; font-family: 'Bitter', serif; padding: 15px; padding-bottom: 10px;">Charting/Email alerts</div>
            <p style="clear: both; text-align: right;"><img src="images/charting-content-separator.png" alt="Separator" border="0" /></p>
            <div style="float: left; margin: 0; padding: 5px 10px 6px 20px;"><img src="images/charting-icon-pencil.png" alt="Functional Drawing Tools!" border="0" /></div>
            <div style="margin: 0; color: #595959; font-family: 'Bitter', serif; padding: 15px; padding-bottom: 10px;">Functional drawing tools</div>
            <p style="clear: both; text-align: right;"><img src="images/charting-content-separator.png" alt="Separator" border="0" /></p>
          </div>

      	<div class="chartingcontentright"><div class="fader" style="display: none;"></div><div class="embedded"><object width="580" height="325"><param name="wmode" value="opaque" /><param name="movie" value="http://www.youtube.com/v/VVNSYsLNcEY?version=3&hl=en_US&start=20&autoplay=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/VVNSYsLNcEY?version=3&amp;hl=en_US&autoplay=1&amp;rel=0" type="application/x-shockwave-flash" width="580" height="325" wmode="opaque" allowscriptaccess="always" allowfullscreen="true"></embed></object><!-- <img src="images/charting-video-box.png" alt="Videos" /> --></div><div style="width:580px; margin: -10px auto; color:#FFFFFF;"><p align="right"><img src="images/icon-arrow-up-black.png" /></p>To watch this video in full screen mode, click the <img src="images/icon-full-screen.png" style="display: inline;" /> icon above.</div> </div>

      </div>

      <div class="chartingcontentmid" style="clear: both;">

          <div class="chartingcontentmidtitle">
          	<div class="chartingcontenttitle" style="float: left;">
           	  <div style="padding: 16px; float: left;"><img src="images/charting-title-arrow.png" alt="Arrow" /></div>
                <div style="padding-top: 9px; color: #313030; font-family: 'Oswald', sans-serif; font-size: 22px; text-shadow: 1px 1px #fcf0c5;">EXCLUSIVE FEATURES</div>
          </div>
          <div style="float: right;"><img src="images/charting-title-bar-right.png" alt="Title Bar Edge" /></div>
          </div>
          <div class="chartingmidvidcontainer" style="text-align: center; margin: 0 auto;">
          <table border="0" cellspacing="10" cellpadding="0" style="text-align: center; margin: 0 auto;">
              <tr>
                <td class="chartingmidvidbox" width="218"><a href="#" class="link1"><img src="images/charting-mid-box-vid.png" alt="Video" style="margin-top: 32px;" /></a><p style="font-family: 'Droid Sans', sans-serif; font-size: 14px; font-weight: bold; color: #000000; text-align: left; padding: 15px; padding-bottom: 0px; line-height: 150%;">100+ Charting Indicators</p><p style="font-family: 'Droid Sans', sans-serif; font-size: 12px; text-align: left; padding: 15px; padding-top: 0px; line-height: 150%;">MTI 4.0 Charting offers over 100 charting indicators right at your fingertips.</p></td>
                <td class="chartingmidvidbox" width="218"><a href="#" class="link2"><img src="images/charting-mid-box-vid.png" alt="Video" style="margin-top: 32px;" /></a>
                <p style="font-family: 'Droid Sans', sans-serif; font-size: 14px; font-weight: bold; color: #000000; text-align: left; padding: 15px; padding-bottom: 0px; line-height: 150%;">30k Bars of Memory</p>
                <p style="font-family: 'Droid Sans', sans-serif; font-size: 12px; text-align: left; padding: 15px; padding-top: 0px; line-height: 150%;">Add history to your charts. See where the market has been to plan for the future.</p></td>
                <td class="chartingmidvidbox" width="218"><a href="#" class="link3"><img src="images/charting-mid-box-vid.png" alt="Video" style="margin-top: 32px;" /></a>
                <p style="font-family: 'Droid Sans', sans-serif; font-size: 14px; font-weight: bold; color: #000000; text-align: left; padding: 15px; padding-bottom: 0px; line-height: 150%;">Forex Trading System</p>
                <p style="font-family: 'Droid Sans', sans-serif; font-size: 12px; text-align: left; padding: 15px; padding-top: 0px; line-height: 150%;">Increase your effectiveness by spoting trends more easily with this back-tested trading system.</p></td>
                <td class="chartingmidvidbox" width="218"><a href="#" class="link4"><img src="images/charting-mid-box-vid.png" alt="Video" style="margin-top: 32px;" /></a><p style="font-family: 'Droid Sans', sans-serif; font-size: 14px; font-weight: bold; color: #000000; text-align: left; padding: 15px; padding-bottom: 0px; line-height: 150%;">Automated System Trader</p>
                <p style="font-family: 'Droid Sans', sans-serif; font-size: 12px; text-align: left; padding: 15px; padding-top: 0px; line-height: 150%;">Automate signals generated by systems on your charting software to your demo trading account.</p></td>
              </tr>
          </table>
        </div>

      </div>

      <div class="chartingordercontent" style="clear: both;">

        <div class="chartingordercontenttitle" style="float: left;">
          	<div class="chartingcontenttitle" style="float: left;">
           	  <div style="padding: 16px; float: left;"><img src="images/charting-title-arrow.png" alt="Arrow" /></div>
                <div style="padding-top: 9px; color: #313030; font-family: 'Oswald', sans-serif; font-size: 22px; text-shadow: 1px 1px #fcf0c5; cursor: pointer;" class="clickme">ORDER NOW</div>
          </div>
          <div style="float: left;"><img src="images/charting-title-bar-right.png" alt="Title Bar Edge" /></div>
          <div style="float: left; font-family: 'Bitter', serif; font-size: 20px; color: #5b5b5b; padding: 5px; text-shadow: 1px 1px #fcf0c5;">Subscription available for only <span style="font-size: 30px; font-weight: bold;">$99</span>/month</div>
          <div style="float: right; padding-top: 5px; padding-right: 5px"><a href="#"  class="clickme"><img src="images/btn-charting-get-started2.png" alt="Get Started!" border="0" /></a></div>
        </div>
        <div class="holder" style="height: 480px;">
        <div class="contentfooter" style="display: none;">
                              <script language="javascript" type="text/javascript" src="https://charting.nextstepfinancialholdings.com/inc/jquery.js"></script>
	<script language="javascript" type="text/javascript">
		var jq = jQuery.noConflict();

		var COUNTRYLIST = '----------|United States of America|Afghanistan|Albania|American Samoa|Andorra|Angola|Anguilla|Antarctica|Antigua and Barbuda|Argentina|Armenia|Aruba|Australia|Austria|Azerbaijan|Bahamas|Bahrain|Bangladesh|Barbados|Belarus|Belgium|Belize|Benin|Bermuda|Bhutan|Bolivia|Bosnia and Herzegovina|Botswana|Bouvet Island|Brazil|British Indian Ocean Territory|Brunei Darussalam|Bulgaria|Burkina Faso|Burundi|Cambodia|Cameroon|Canada|Cape Verde|Cayman Islands|Central African Republic|Chad|Chile|China|Christmas Island|Cocos (Keeling) Islands|Colombia|Comoros|Congo|Cook Islands|Costa Rica|Cuba|Czech Republic|Denmark|Djibouti|Dominica|Dominican Republic|East Timor|Ecuador|Egypt|El salvador|Equatorial Guinea|Eritrea|Estonia|Ethiopia|Falkland Islands|Faroe Islands|Fiji|Findland|France|French Guiana|French Polynesia|French Southern Territories|Gabon|Gambia|Georgia|Germany|Ghana|Gibraltar|Greece|Greenland|Grenada|Guadeloupe|Guam|Guatemala|Guinea|Guinea-Bissau|Guyana|Haiti|Heard Island and McDonald Islands|Holy See (Vatican City State)|Honduras|Hungary|Iceland|India|Indonesia|Iran|Iraq|Ireland|Israel|Italy|Jamaica|Japan|Jordan|Kazakstan|Kenya|Kiribati|KN&quot; - Saint Kitts and Nevis|Kuwait|Kyrgystan|Lao|Latvia|Lebanon|Lesotho|Libyan Arab Jamahiriya|Liechtenstein|Lithuania|Luxembourg|Macau|Macedonia (FYR)|Madagascar|Malawi|Malaysia|Maldives|Mali|Malta|Marshall Islands|Martinique|Mauritania|Mauritius|Mayotte|Mexico|Micronesia|Moldova|Monaco|Mongolia|Montserrat|Morocco|Mozambique|Myanmar|Namibia|Nauru|Nepal|Netherlands|Netherlands Antilles|Neutral Zone|New Caledonia|New Zealand|Nicaragua|Niger|Nigeria|Niue|Norfolk Island|Northern Mariana Islands|North Korea|Norway|Oman|Pakistan|Palau|Panama|Papua New Guinea|Paraguay|Peru|Philippines|Pitcairn|Poland|Portugal|Puerto Rico|Reunion|Romania|Russian Federation|Rwanda|Saint Helena|Saint Lucia|Saint Pierre and Miquelon|Saint Vincent and the Grenadines|Samoa|San Marino|Sao Tome and Principe|Saudi Arabia|Senegal|Seychelles|Sierra Leone|Singapore|Slovakia|Slovenia|Somalia|South Africa|South Georgia|South Korea|Spain|Sri Lanka|Sudan|Suriname|Svalbard and Jan Mayen Islands|Swaziland|Sweden|Switzerland|Syria|Taiwan|Tajikistand|Tanzania|Thailand|Togo|Tokelau|Tonga|Trinidad and Tobago|Tunisia|Turkey|Turkmenistan|Cape Turks and Caicos Islands|Tuvalu|Uganda|Ukraine|United Arab Emirates|United Kingdom|United States Minor Outlying Islands|Uruguay|Uzbekistan|Vanuatu|Venezuela|Vietnam|Virgin Islands (British)|Virgin Islands (U.S.)|Western Sahara|Wallis and Futuna Islands|Yemen|Yugoslavia|Zaira|Zambia|Zimbabwe';
		var STATELIST = 'United States of America:AK:Alaska|United States of America:AL:Alabama|United States of America:AR:Arkansas|United States of America:AZ:Arizona|United States of America:CA:California|United States of America:CO:Colorado|United States of America:CT:Connecticut|United States of America:DE:Delaware|United States of America:FL:Florida|United States of America:GA:Georgia|United States of America:HI:Hawaii|United States of America:IA:Iowa|United States of America:ID:Idaho|United States of America:IL:Illinois|United States of America:IN:Indiana|United States of America:KS:Kansas|United States of America:KY:Kentucky|United States of America:LA:Louisiana|United States of America:MA:Massachusetts|United States of America:MD:Maryland|United States of America:ME:Maine|United States of America:MI:Michigan|United States of America:MN:Minnesota|United States of America:MO:Missouri|United States of America:MS:Mississippi|United States of America:MT:Montana|United States of America:NC:North Carolina|United States of America:ND:North Dakota|United States of America:NE:Nebraska|United States of America:NH:New Hampshire|United States of America:NJ:New Jersey|United States of America:NM:New Mexico|United States of America:NV:Nevada|United States of America:NY:New York|United States of America:OH:Ohio|United States of America:OK:Oklahoma|United States of America:OR:Oregon|United States of America:PA:Pennsylvania|United States of America:RI:Rhode Island|United States of America:SC:South Carolina|United States of America:SD:South Dakota|United States of America:TN:Tennessee|United States of America:TX:Texas|United States of America:UT:Utah|United States of America:VA:Virginia|United States of America:VT:Vermont|United States of America:WA:Washington|United States of America:WI:Wisconsin|United States of America:WV:West Virginia|United States of America:WY:Wyoming|Canada:AB:Alberta|Canada:BC:British Columbia|Canada:MB:Manitoba|Canada:NB:New Brunswick|Canada:NL:Newfoundland and Labrador|Canada:NS:Nova Scotia|Canada:NT:Northwest Territories|Canada:NU:Nunavut|Canada:ON:Ontario|Canada:PE:Prince Edward Island|Canada:QC:Quebec|Canada:SK:Saskatchewan|Canada:YT:Yukon Territory|Australia:AAT:Australian Antarctic Territory|Australia:ACT:Australian Capital Territory|Australia:NT:Northern Territory|Australia:NSW:New South Wales|Australia:QLD:Queensland|Australia:SA:South Australia|Australia:TAS:Tasmania|Australia:VIC:Victoria|Australia:WA:Western Australia|Brazil:AC:Acre|Brazil:AL:Alagoas|Brazil:AM:Amazonas|Brazil:AP:Amapa|Brazil:BA:Baia|Brazil:CE:Ceara|Brazil:DF:Distrito Federal|Brazil:ES:Espirito Santo|Brazil:FN:Fernando de Noronha|Brazil:GO:Goias|Brazil:MA:Maranhao|Brazil:MG:Minas Gerais|Brazil:MS:Mato Grosso do Sul|Brazil:MT:Mato Grosso|Brazil:PA:Para|Brazil:PB:Paraiba|Brazil:PE:Pernambuco|Brazil:PI:Piaui|Brazil:PR:Parana|Brazil:RJ:Rio de Janeiro|Brazil:RN:Rio Grande do Norte|Brazil:RO:Rondonia|Brazil:RR:Roraima|Brazil:RS:Rio Grande do Sul|Brazil:SC:Santa Catarina|Brazil:SE:Sergipe|Brazil:SP:Sao Paulo|Brazil:TO:Tocatins|United Kingdom:AN:Antrim|United Kingdom:AVON:Avon|United Kingdom:BEDS:Bedfordshire|United Kingdom:BERKS:Berkshire|United Kingdom:BUCKS:Buckinghamshire|United Kingdom:CAMBS:Cambridgeshire|United Kingdom:CHESH:Cheshire|United Kingdom:CLEVE:Cleveland|United Kingdom:CORN:Cornwall|United Kingdom:CUMB:Cumbria|United Kingdom:DERBY:Derbyshire|United Kingdom:DEVON:Devon|United Kingdom:DORSET:Dorset|United Kingdom:DURHAM:Durham|United Kingdom:ESSEX:Essex|United Kingdom:GLOUS:Gloucestershire|United Kingdom:GLONDON:Greater London|United Kingdom:GMANCH:Greater Manchester|United Kingdom:HANTS:Hampshire|United Kingdom:HERWOR:Hereford & Worcestershire|United Kingdom:HERTS:Hertfordshire|United Kingdom:HUMBER:Humberside|United Kingdom:IOM:Isle of Man|United Kingdom:IOW:Isle of Wight|United Kingdom:KENT:Kent|United Kingdom:LANCS:Lancashire|United Kingdom:LEICS:Leicestershire|United Kingdom:LINCS:Lincolnshire|United Kingdom:MERSEY:Merseyside|United Kingdom:NORF:Norfolk|United Kingdom:NHANTS:Northamptonshire|United Kingdom:NTHUMB:Northumberland|United Kingdom:NOTTS:Nottinghamshire|United Kingdom:OXON:Oxfordshire|United Kingdom:SHROPS:Shropshire|United Kingdom:SOM:Somerset|United Kingdom:STAFFS:Staffordshire|United Kingdom:SUFF:Suffolk|United Kingdom:SURREY:Surrey|United Kingdom:SUSS:Sussex|United Kingdom:WARKS:Warwickshire|United Kingdom:WMID:West Midlands|United Kingdom:WILTS:Wiltshire|United Kingdom:YORK:Yorkshire|Mexico:Aguascalientes:Aguascalientes|Mexico:Baja California:Baja California|Mexico:Baja California Sur:Baja California Sur|Mexico:Campeche:Campeche|Mexico:Chiapas:Chiapas|Mexico:Chihuahua:Chihuahua|Mexico:Coahuila:Coahuila|Mexico:Colima:Colima|Mexico:Distrito Federal:Distrito Federal|Mexico:Durango:Durango|Mexico:Guanajuato:Guanajuato|Mexico:Guerrero:Guerrero|Mexico:Hidalgo:Hidalgo|Mexico:Jalisco:Jalisco|Mexico:México:México|Mexico:Michoacán:Michoacán|Mexico:Morelos:Morelos|Mexico:Nayarit:Nayarit|Mexico:Nuevo León:Nuevo León|Mexico:Oaxaca:Oaxaca|Mexico:Puebla:Puebla|Mexico:Querétaro:Querétaro|Mexico:Quintana Roo:Quintana Roo|Mexico:San Luis Potosí:San Luis Potosí|Mexico:Sinaloa:Sinaloa|Mexico:Sonora:Sonora|Mexico:Tabasco:Tabasco|Mexico:Tamaulipas:Tamaulipas|Mexico:Tlaxcala:Tlaxcala|Mexico:Veracruz:Veracruz|Mexico:Yucatán:Yucatán|Mexico:Zacatecas:Zacatecas|Trinidad and Tobago:Port of Spain:Port of Spain|Trinidad and Tobago:San Fernando:San Fernando|Trinidad and Tobago:Chaguanas:Chaguanas|Trinidad and Tobago:Arima Borough:Arima Borough|Trinidad and Tobago:Point Fortin:Point Fortin|Trinidad and Tobago:Couva-Tabaquite-Talparo:Couva-Tabaquite-Talparo|Trinidad and Tobago:Diego Martin:Diego Martin|Trinidad and Tobago:Penal-Debe:Penal-Debe|Trinidad and Tobago:Princes Town:Princes Town|Trinidad and Tobago:Rio Claro-Mayaro:Rio Claro-Mayaro|Trinidad and Tobago:San Juan-Laventille:San Juan-Laventille|Trinidad and Tobago:Sangre Grande:Sangre Grande|Trinidad and Tobago:Siparia:Siparia|Trinidad and Tobago:Tunapuna-Piarco:Tunapuna-Piarco';
		var COUNTRY = 0;
		var ABBREVIATION = 1;
		var STATE = 2;

		var zipValid = ('<?=$zip?>' == '');
		var emailValid = ('<?=$email?>' == '');
		var phoneValid = ('<?=$phone?>' == '');
		var ccnumValid = false;
		var ccexpValid = false;
		var ccvValid = false;

		jq(function(){
			var countries = COUNTRYLIST.split('|');
			for (var country in countries)
			{
				var selected = countries[country] == '<?=$country?>' ? 'selected="selected"' : '';
				jq('#id_country').append('<option value="' + countries[country] + '" ' + selected + '>' + countries[country] + '</option>');
			}

			jq('#id_country').change(function(){
				var s = getStates(jq('#id_country').val());
				if (s == '')
					jq('#stateinput').html('<input type="text" id="state" name="x_state" />');
				else
					jq('#stateinput').html('<select id="state" name="x_state" style="width: 147px;">' + s + '</select>');
			})

			jq('#email').blur(function(){
				if (!jq('#email').val().match(/^[A-Za-z][A-Za-z0-9\._-]*@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$/))
				{
					jq('#email').addClass('error');
					jq('#email_error').removeClass('hidden');
					emailValid = false;
				}
				else
				{
					jq('#email').removeClass('error');
					jq('#email_error').addClass('hidden');
					emailValid = true;
				}
			})

			jq('#phone').blur(function(){
				if (jq('#phone').val().length < 10 || !jq('#phone').val().match(/^([0-9]*[- .]?)*[0-9]+[- .]?[0-9]+[- .]?[0-9]+$/))
				{
					phoneValid = false;
					jq('#phone').addClass('error');
				}
				else
				{
					phoneValid = true;
					jq('#phone').removeClass('error');
				}
			})

			jq('#ccnumber').blur(function(){
				if (!jq('#ccnumber').val().match(/^[0-9]{15,16}$/))
				{
					ccnumValid = false;
					jq('#ccnumber').addClass('error');
				}
				else
				{
					ccnumValid = true;
					jq('#ccnumber').removeClass('error');
				}
			})
			jq('#expiration').blur(function(){
				if (!jq('#expiration').val().match(/^((0[1-9])|(1[0-2]))[0-9]{2}$/))
				{
					ccexpValid = false;
					jq('#expiration').addClass('error');
				}
				else
				{
					ccexpValid = true;
					jq('#expiration').removeClass('error');
				}
			})

			jq('#firstname').blur(function(){
				if (jq('#firstname').val() == '')
					jq('#firstname').addClass('error');
				else
					jq('#firstname').removeClass('error');
			})
			jq('#lastname').blur(function(){
				if (jq('#lastname').val() == '')
					jq('#lastname').addClass('error');
				else
					jq('#lastname').removeClass('error');
			})
			jq('#address').blur(function(){
				if (jq('#address').val() == '')
					jq('#address').addClass('error');
				else
					jq('#address').removeClass('error');
			})
			jq('#city').blur(function(){
				if (jq('#city').val() == '')
					jq('#city').addClass('error');
				else
					jq('#city').removeClass('error');
			})
			jq('#state').blur(function(){
				if (jq('#state').val() == '')
					jq('#state').addClass('error');
				else
					jq('#state').removeClass('error');
			})
			jq('#zip').blur(function(){
				if (jq('#zip').val() == '')
					jq('#zip').addClass('error');
				else
					jq('#zip').removeClass('error');
			})
			jq('#ccv').blur(function(){
				if (!jq('#ccv').val().match(/^[0-9]{3,4}$/))
				{
					ccvValid = false;
					jq('#ccv').addClass('error');
				}
				else
				{
					ccvValid = true;
					jq('#ccv').removeClass('error');
				}
			})

			jq('#validate').click(validate_form);
		});


		function validate_form()
		{
			jq('#validate').unbind('click');
			jq('#validate').attr('src', 'http://www.markettraders.com/99/img/btn-processing.gif');

			var message = '';

			if (jq('#firstname').val() == '')
				message += ' - Please enter a first name.\n';
			if (jq('#lastname').val() == '')
				message += ' - Please enter a last name.\n';
			if (jq('#address').val() == '')
				message += ' - Please enter a street address.\n';
			if (jq('#city').val() == '')
				message += ' - Please enter a city.\n';
			if (jq('#state').val() == '' || jq('#state').val() == '----------')
				message += ' - Please enter a state or province.\n';
			if (jq('#zip').val() == '')
				message += ' - Please enter a ZIP or postal code.\n';
			if (jq('#id_country').val() == '' || jq('#id_country').val() == '----------')
				message += ' - Please select a country.\n';
			jq('#email').blur();
			if (!emailValid)
				message += ' - Please enter a valid email address.\n';
			jq('#phone').blur();
			if (!phoneValid)
				message += ' - Please enter a phone number.\n';
			jq('#ccnumber').blur();
			if (!ccnumValid)
				message += ' - Please enter a valid 15- or 16-digit credit card number, using numbers only. Do not enter spaces or dashes.\n';
			jq('#ccv').blur();
			if (!ccvValid)
				message += ' - Please enter a valid 3-digit or 4-digit CCV code. This code is usually found on the back of your credit card in the signature area.\n';
			jq('#expiration').blur();
			if (!ccexpValid)
				message += ' - Please enter an expiration date in the format MMYY.\n';
			if (jq('#unhash').val() == 0)
			{
				if (jq('#username').val() == '')
					message += ' - Please enter a username.\n';
				if (jq('#password').val().length < 6)
					message += ' - Please enter a password at least 6 characters in length.\n';
			}
			if (!jq('#acknowledge').attr('checked'))
				message += ' - Please acknowledge the credit card agreement.';

			if (message == '')
			{
				jq.post('getaccount.php', {
					email: jq('#email').val(),
					phone: jq('#phone').val(),
					fname: jq('#firstname').val(),
					lname: jq('#lastname').val(),
					address: jq('#address').val(),
					city: jq('#city').val(),
					state: jq('#state').val(),
					zip: jq('#zip').val(),
					ccnumber: jq('#ccnumber').val(),
					ccexpiration: jq('#expiration').val(),
					country: jq('#id_country').val(),
					ccv: jq('#ccv').val(),
					sfid: jq('#sfid').val(),
					ip: jq('#ip').val(),
					un: jq('#username').val(),
					pw: jq('#password').val(),
					unhash: jq('#unhash').val()
				}, function(data){
				alert(data);
					var result = data.split(';');
					if (result[0] == 2)
					{
						alert('The charting username you entered exists, but does not match the password in our database. If you are a new client, please choose a different username.');
						jq('#validate').attr('src', 'img/btn-purchase-subscription.png');
						jq('#validate').click(validate_form);
					}
					else if (result[0] == 1)
					{
						jq('#sfid').val(result[1]);
						jq('form').append('<input type="hidden" name="sfid" value="'+result[1]+'">');
						jq('#submit').click();
					}
					else if (result[0] == 0)
					{
						alert('There was a problem with the information you submitted. Please check the data you entered and try again.');
						jq('#validate').attr('src', 'img/btn-purchase-subscription.png');
						jq('#validate').click(validate_form);
					}
					else
					{
						alert('An error occurred in our system while submitting your information. Please try again in a moment.');
						jq('#validate').attr('src', 'img/btn-purchase-subscription.png');
						jq('#validate').click(validate_form);
					}
				});
				return false;
			}
			else
			{
				alert(message);
				jq('#validate').attr('src', 'img/btn-purchase-subscription.png');
				jq('#validate').click(validate_form);
				return false;
			}
		}

		function getStates(country){
			var opts = '';

			var states = STATELIST.split('|');
			for (var stateinfo in states)
			{
				var state = states[stateinfo].split(':');
				if (state[COUNTRY] == country)
					opts += '<option value="' + state[ABBREVIATION] + '">' + state[STATE] + '</option>';
			}

			if (opts != '')
				opts = '<option value="" selected="selected">----------</option>' + opts;

			return opts;
		}
	</script>
<!-- https://secure.authorize.net/gateway/transact.dll -->
        <form action="https://secure.authorize.net/gateway/transact.dll" method="post">
        <div id="step-1"><span class="step-title">STEP 1 -Set up Account</span><br /><span class="step-title-sub">Enter billing information</span><br />

			<input type="hidden" name="test" value="0" />
			<? echo $authorizeNetFields; ?>
			<table  width="100%" align="center">
				<tr>
					<td>
					<!-- table here -->
			        <table width="100%" cellpadding="0" cellspacing="5">
					<tr><td class="title">Billing Street Address:</td></tr><tr><td colspan="2" class="field"><input id="address" type="text" name="x_address" style="width: 100%;" value="<?=$address?>" /></td></tr>
					<tr>
						<td class="title">Billing City:</td><td class="title">Billing State/Province:</td></tr><tr><td class="field"><input id="city" type="text" name="x_city" value="<?=$city?>" /></td>
						<td class="field"><span id="stateinput"><input id="state" type="text" name="x_state" value="<?=$state?>" <?=$statedisabled?> /></span></td>
					</tr>
					</table>
					<!-- table here -->
					<table width="100%" cellpadding="0" cellspacing="5">
					<tr>
						<td width="183" class="title">Billing Country:</td><td width="105" class="title" align="right">ZIP/Postal Code:</td>
					</tr><tr><td class="field"><select name="x_country" id="id_country" style="width: 179px;"></select></td>
						<td class="field" align="right"><input id="zip" type="text" name="x_zip" style="width: 100px;" value="<?=$zip?>" /></td>
					</tr>
					</table>
					<!-- table here -->
					<table width="100%" cellpadding="0" cellspacing="5">
					<tr>
						<td class="title" valign="bottom">Credit Card Number (Numbers Only):</td><td width="102" class="title" align="right">Expiration Date (MMYY):</td>
					</tr><tr><td class="field"><input id="ccnumber" type="text" name="x_card_num" value="" maxlength="16" style="width:183px;"/></td>
						<td class="field" align="right"><input id="expiration" type="text" name="x_exp_date" value="EX: 1214" onclick="if(this.value = 'EX: 1214') this.value = '';" maxlength="4" style="width:100px;" /></td>
					</tr>
					<tr>
						<td class="title">&nbsp;</td><td width="102" class="title" align="right">CCV:</td>
					</tr><tr><td class="field">&nbsp;</td>
						<td class="field" align="right"><input id="ccv" type="text" name="ccv" value="" maxlength="4" style="width:100px;" /></td>
					</tr>
						<?//=$loginsection?>
					</table>
				</td>
			</tr>
			</table>
			      </div>
			            <div id="step-2"><span class="step-title">STEP 2 -Create Profile</span><br /><span class="step-title-sub">Enter Your Information</span><br />

			            <!-- FORM -->
			            <table  width="300" align="center">
				<tr>
					<td>
			        <table width="100%" cellpadding="0" cellspacing="5">
					<tr>
						<td width="182" class="title">First Name:</td><td width="145" class="title">Last Name:</td>
					</tr><tr><td class="field"><input id="firstname" type="text" name="x_first_name" value="<?=$fname?>" /></td>
						<td class="field"><input id="lastname" type="text" name="x_last_name" value="<?=$lname?>" /></td>
					</tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="5">
					<tr>
						<td width="56%" class="title">Email Address:</td>
						<td width="44%" class="title">Phone Number:</td>
					</tr><tr><td class="field"><input id="email" type="text" name="x_email" value="<?=$email?>" /></td>
						<td class="field"><input id="phone" type="text" name="x_phone" value="<?=$phone?>"/></td>
					</tr>
					</table>

				</td>
			</tr>
			</table>
            <br /><br />
            <span class="step-title-sub">Setup Profile</span><br />
        	Choose a username which you will use for logging into your charting and displayed inside Forextips.com<br />
            <!-- FORM -->
            <table width="100%" cellpadding="0" cellspacing="5">
            <tr>
			<td class="title">Charting Username:</td><td class="title">Charting Password:</td>
		</tr><tr>
			<td class="field">
				<input id="unhash" type="hidden" name="unhash" value="0" />
				<input id="username" name="username" type="text" />
			</td>
			<td class="field"><input id="password" name="password" type="password" /></td>
		</tr><tr><td class="notavailable" colspan="2"></td></tr></table>
      </div>
          <div id="step-3">
          <span class="step-title">STEP 3 -Finalize</span><br /><span class="step-title-sub">Agree to Terms</span><br />
          <br />
        	<input type="checkbox" name="acknowledge" id="acknowledge" align="left"/> I acknowledge that I will be enrolled in the autobill program and my credit card will be charged $99 every month on the beginning of my billing cycle.
            <a href="/privacy-policy/">View Privacy Policy</a><br />
            <!-- FORM -->
            <img src="img/btn-purchase-subscription.png" width="169" height="40" id="validate" style="cursor: pointer;"/>
		<div style="display: none;"><input type="submit" id="submit" /></div>
            <input type="hidden" name="submitted" value="true" />
          <p align="center"><img src="img/authorize_net_87x69.png" /><br /><br /><img src="img/VS_GD_light.gif" width="132" height="31" /></p></div>
            <div class="clear"></div>
        </div>
    </div>
    </form>
    </div>

</body>
</html>
