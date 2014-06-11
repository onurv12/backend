<?php

require 'config/database.php';
require 'vendor/mikecao/flight/flight/Flight.php';
require '../dbWrapper/dbWrapper.class.php';
require '../userManagement/userManager.class.php';
require 'validation.class.php';
require 'controller/User.class.php';


$dbSettings = Array();
// DBName
$dbSettings[] = DBName;
//DBUser
$dbSettings[] = DBUser;
//DBPassword
$dbSettings[] = DBPassword;

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
	UserController::login();
});

Flight::route('GET /logout', function () {
	UserController::logout();
});

// Get the session of the logged in user
Flight::route('GET /user', function () {
	UserController::getSession();
});

Flight::route('GET /isAdministratorLoggedIn', function() {
	$DB = Flight::DB();
	$userManager = Flight::userManager();
	if ($userManager->getIsLoggedInAsAdministrator())
		echo json_encode(true);
	else
		echo json_encode(false);
});

Flight::route('GET /activeUsers', function() {
	$DB = Flight::DB();
	$userManager = Flight::userManager();
	$allUsers = $userManager->getAllActiveUsers();
	if ($allUsers) {
		echo json_encode($allUsers);
	} else {
		Flight::halt(400, "400 - Bad Request");//TODO: BEtter error code?
	}
});

Flight::route('GET /suspendedUsers', function() {
	$DB = Flight::DB();
	$userManager = Flight::userManager();
	$allUsers = $userManager->getAllSuspendedUsers();
	if ($allUsers) {
		echo json_encode($allUsers);
	} else {
		$allUsers = Array();
		echo json_encode($allUsers);
	}
});

//Activate user
Flight::route('POST /activateUser', function() {
	$DB = Flight::DB();
	$userManager = Flight::userManager();
	if (!$userManager->getIsLoggedInAsAdministrator())
		Flight::halt(401, "401 Unauthorized - You are not logged in as administrator.");
	
	$request = Flight::request();
	$json = json_decode($request->body, true);
	if (isset($json["Username"])) {
		$userManager->activateUser($json["Username"]);
		echo json_encode(true);
	} else {
		echo json_encode(false);
	}
});

// Registration
Flight::route('POST /user', function () {
	UserController::registration();
});

//////////////////////////////////////////////////////

// Initialize Flight
Flight::start();


?>
