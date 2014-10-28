<?php

require 'config/database.php';
require 'vendor/mikecao/flight/flight/Flight.php';
require 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';
require '../dbWrapper/dbWrapper.class.php';
require 'manager/UserManager.class.php';
require 'manager/ProductionManager.class.php';
require 'validation.class.php';
require 'controller/User.class.php';
require 'controller/Project.class.php';
require 'controller/Asset.class.php';

$dbSettings = Array();
// DBName
$dbSettings[] = DBName;
//DBUser
$dbSettings[] = DBUser;
//DBPassword
$dbSettings[] = DBPassword;
//DBHost
$dbSettings[] = DBHost;
//DBPort
$dbSettings[] = DBPort;

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

Flight::route('GET /user/@id', function($id) {
	UserController::getUser($id);
});

Flight::route('GET /user/@id/projects', function($id) {
	ProjectController::getProjectsOfUser($id);
});

Flight::route('POST /user/randomPass', function() {
	UserController::sendRandomPassword();
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

Flight::route('PUT /project/@projectID', function($projectID) {
	ProjectController::updateProject($projectID);
});

Flight::route('DELETE /project/@projectID', function($projectID) {
	ProjectController::deleteProject($projectID);
});

Flight::route('PUT /project', function() {
	ProjectController::editProject();
});

// Get all projects
Flight::route('GET /projects', function() {
	ProjectController::getAllProjects();
});

// Get the projects the user belongs
Flight::route('GET /projects/belonged', function() {
	ProjectController::getBelongedProjects();
});

Flight::route('GET /project/@id', function ($id) {
	ProjectController::get($id);
});

Flight::route('GET /project/@id/users', function ($id) {
	ProjectController::getProjectUsers($id);
});

Flight::route('PUT /project/@id/users', function ($id) {
	ProjectController::updateUsers($id);
});

Flight::route('PUT /project/@id', function ($id) {
	ProjectController::updateProject($id);
});

Flight::route('GET /project/@projectID/canvas/@canvasID', function ($projectID, $canvasID) {
	ProjectController::getCanvas($projectID, $canvasID);
});

Flight::route('POST /project/@projectID/canvas', function ($projectID) {
	ProjectController::newCanvas($projectID);
});

Flight::route('PUT /project/@projectID/canvas/@canvasID', function ($projectID, $canvasID) {
	ProjectController::saveCanvas($projectID, $canvasID);
});

Flight::route('DELETE /project/@projectID/canvas/@canvasID', function ($projectID, $canvasID) {
	ProjectController::removeCanvas($projectID, $canvasID);
});

//Post new comment
Flight::route('POST /project/@projectID/comment', function ($projectID) {
	ProjectController::addComment($projectID);
});

//Remove a comment
Flight::route('DELETE /project/@projectID/comment/@commentID', function ($projectID, $commentID) {
	ProjectController::deleteComment($projectID, $commentID);
});

//Get all comments of a certain project
Flight::route('GET /project/@projectID/comments', function ($projectID) {
	ProjectController::getAllComments($projectID);
});

Flight::route('POST /project/@projectID/canvas/@canvasID/assets/@assetID', function ($projectID, $canvasID, $assetID) {
	AssetController::addAssetToCanvas($projectID, $canvasID, $assetID);
});

Flight::route('DELETE /project/@projectID/canvas/@canvasID/assets/@assetID', function ($projectID, $canvasID, $assetID) {
	AssetController::removeAsset($projectID, $canvasID, $assetID);
});

Flight::route('GET /tags', function () {
	AssetController::getTags();
});

Flight::route('POST /tags', function () {
	AssetController::createTag();
});

Flight::route('GET /_assets', function () {
	AssetController::getAssets();
});

Flight::route('POST /_assets', function () {
	AssetController::uploadAsset();
});

Flight::route('POST /asset/@assetID/tag/@tagID', function ($assetID, $tagID) {
	AssetController::tagAsset($assetID, $tagID);
});

//Activate user
Flight::route('POST /user/activate', function() {
	UserController::activateUser();
});

Flight::route('PUT /user', function() {
	UserController::updateUser();
});

Flight::route('DELETE /user/@id', function($id) {
	UserController::deleteUser($id);
});

// Registration
Flight::route('POST /user', function () {
	UserController::registration();
});

//////////////////////////////////////////////////////

// Initialize Flight
Flight::start();


?>
