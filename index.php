<?php

require 'vendor/mikecao/flight/flight/Flight.php';
require '../dbWrapper/dbWrapper.class.php';
require '../userManagement/userManager.class.php';

$dbSettings = Array();
// DBName
$dbSettings[] = "storyboard";
//DBUser
$dbSettings[] = "storyboard";
//DBPassword
$dbSettings[] = "storyboard";

Flight::register( 'DB', 'dbWrapper', $dbSettings );
$DB = Flight::DB();
Flight::register( 'userManager', 'userManager', array($DB) );

//////////////////////////////////////////////////////
// Routes
//////////////////////////////////////////////////////

Flight::route('/', function () {

    $userManager = Flight::userManager();

    if ($userManager->getLoginState()) {
    	echo "Yep. It works.";
    } else {
    	Flight::halt(401, "401 Unauthorized - You are not logged in, sports.");
    }
});

Flight::route('GET /login', function () {

	$DB = Flight::DB();
	$userManager = Flight::userManager();

	$headers = apache_request_headers();
	$request = Flight::request();

	if (isset($headers["username"], $headers["password"])) {
		try {
			$userdata = $userManager->login($headers["username"], $headers["password"]);

			if ($userdata) {
				echo json_encode(true);
			} else {
				echo json_encode(false);
			}
		} catch (Exception $e) {
			Flight::halt(401, "401 - User suspended");
		}
	} else {
		Flight::halt(400, "400 - Bad Request");
	}

});

Flight::route('GET /logout', function () {

	$userManager = Flight::userManager();

	$userManager->logout();
	echo "";
});

//////////////////////////////////////////////////////

// Initialize Flight
Flight::start();


?>
