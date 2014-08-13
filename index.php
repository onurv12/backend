<?php

require 'config/database.php';
require 'vendor/mikecao/flight/flight/Flight.php';
require 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';
require '../dbWrapper/dbWrapper.class.php';
require '../userManagement/UserManager.class.php';
require '../userManagement/ProductionManager.class.php';
require 'validation.class.php';
require 'controller/User.class.php';
require 'controller/Project.class.php';

$dbSettings = Array();
// DBName
$dbSettings[] = DBName;
//DBUser
$dbSettings[] = DBUser;
//DBPassword
$dbSettings[] = DBPassword;

Flight::register( 'DB', 'dbWrapper', $dbSettings );
$DB = Flight::DB();
Flight::register( 'UserManager', 'UserManager', array($DB) );
Flight::register( 'ProductionManager', 'ProductionManager', array($DB) );

//////////////////////////////////////////////////////
// Routes
//////////////////////////////////////////////////////

Flight::route('/', function () {

    $userManager = Flight::UserManager();

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

//Create new project
Flight::route('POST /project', function() {
	ProjectController::createProject();
});

// Get all projects
Flight::route('GET /projects', function() {
	ProjectController::getAllProjects();
});

// Get the projects the user belongs
Flight::route('GET /projects/belonged', function() {
	ProjectController::getBelongedProjects();
});

//Activate user
Flight::route('POST /user/activate', function() {
	UserController::activateUser();
});

Flight::route('PUT /user', function() {
	//TODO: Add a detection which evaluates what kind of change it is
	UserController::changeRole();
});

// Registration
Flight::route('POST /user', function () {
	UserController::registration();
});

//////////////////////////////////////////////////////

// Initialize Flight
Flight::start();


?>
