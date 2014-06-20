<?php

require 'config/database.php';
require 'vendor/mikecao/flight/flight/Flight.php';
require 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';
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

Flight::route('GET /users/active', function() {
	UserController::getActiveUsers();
});

Flight::route('GET /users/suspended', function() {
	UserController::getSuspendedUsers();
});

//Activate user
Flight::route('POST /user/activate', function() {
	UserController::activateUser();
});

Flight::route('POST /user/change', function(){
	UserController::changeLevel();
});

// Registration
Flight::route('POST /user', function () {
	UserController::registration();
});

//////////////////////////////////////////////////////

// Initialize Flight
Flight::start();


?>
