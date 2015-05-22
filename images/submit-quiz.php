<?php
	define('APP_ID', '270406523013190');
	define('APP_SECRET', '4d4b835f8403616b3136363d3bf6ad2d');

	$answerKey = array(
		'b',
		'c',
		'b',
		'd',
		'b',
		'b',
		'd',
		'a',
		'b',
		'e',
		'a',
		'c'
	);

	$answers = json_decode(stripslashes($_GET['answers']), true);
	$score = 0;

	for ($i = 0; $i < sizeof($answers); $i++)
		if (strcasecmp($answers[$i], $answerKey[$i]) == 0)
			$score++;

	$score = ceil(($score / 12) * 100);

	if ($score > 90)
		$result = array(
			'type' => 'a Professional Trader',
			'description' => 'Congratulations! You are well on your way to becoming a professional currency trader. You should investigate Continuing Education opportunities with Market Traders Institute, one of the world’s oldest and most trusted Forex education firms.'
		);
	else if ($score > 60 && $score <= 90)
		$result = array(
			'type' => 'an Intermediate Trader',
			'description' => 'Good job! With just a little more knowledge, you could be well on your way to becoming a professional currency trader.'
		);
	else if ($score > 30 && $score <= 60)
		$result = array(
			'type' => "a Trader's Apprentice",
			'description' => 'You already have some of the knowledge necessary to trade currencies.'
		);
	else
		$result = array(
			'type' => 'a Novice Forex Trader',
			'description' => "You've come to the right place to get essential Forex knowledge. The Ultimate Traders Package on Demand and Market Traders Institute will prepare you to take on the Forex market by providing you with the tools, training, support and confidence essential to Forex success. To get started, attend a complimentary Forex presentation to learn where to start. A Forex professional will be happy to show you what sets us apart from other Forex educators, as well as, walk you through the LIVE market and show you ways in which you could increase your return on investment and get an edge in the Forex right off the bat."
		);

	$uid = $_GET['uid'];
	$username = $_GET['username'];

	require_once('/var/www/WebProduction/content.markettraders.com/php/facebook-api/Facebook.php');

	$fb = new Facebook(array(
		'appId' => APP_ID,
		'secret' => APP_SECRET
	));

	$fields = array(
		'link' => 'http://apps.facebook.com/forex-iq-quiz/',
		'message' => 'I just took the Forex IQ Quiz, and found out that I am ' . $result['type'] . ".\n\n" . $result['description'],
		'name' => 'Forex IQ Quiz',
		'description' => 'Find out what kind of trader you are!'
	);

	// If the try fails, then we haven't been authorized to post for this user. Simply continue on to the results page.
	try {
		$fb->api("/$username/feed", 'post', $fields);
	} catch (FacebookApiException $e) {}

	header('Location: results.php?data=' . json_encode($result));
?>