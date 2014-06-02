<?php

require 'config/database.php';
require 'vendor/mikecao/flight/flight/Flight.php';
require '../dbWrapper/dbWrapper.class.php';
require '../userManagement/userManager.class.php';
require 'validation.class.php';

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

// Registration
Flight::route('POST /user', function () {

	$userManager = Flight::userManager();

	if (!$userManager->getLoginState()) {
		$request = Flight::request();
		$json = json_decode($request->body, true);

		if (!isset($json["GravatarEmail"])) {
			$json["GravatarEmail"] = null;
		}

		
		if (isset($json["Name"], $json["Fullname"], $json["Password"], $json["Email"])) {
			var_dump(validation::minLength($json["Name"], 3), validation::minLength($json["Fullname"], 3), validation::minLength($json["Password"], 6), validation::email($json["Email"]), $json["GravatarEmail"] == null, validation::email($json["GravatarEmail"]));

			if (!(validation::minLength($json["Name"], 3) && validation::minLength($json["Fullname"], 3) && validation::minLength($json["Password"], 6) && validation::email($json["Email"]) && ($json["GravatarEmail"] == null || validation::email($json["GravatarEmail"])))) {
				Flight::halt(400, "Bad request.");
			}

			try {
				$userManager->createUser($json["Name"], $json["Fullname"], $json["Password"], $json["Email"], $json["GravatarEmail"]);
			} catch (Exception $e) {
				Flight::halt(409, "Conflict. User already exists.");
			}

			// TODO: Send a mail here
			echo "true";
		} else {
			Flight::halt(400, "Missing information");
		}
	} else {
		Flight::halt(412, "412 - You may not be logged in while registering a new user.");
	}
});

//////////////////////////////////////////////////////

// Initialize Flight
Flight::start();


?>
