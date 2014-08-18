<?php

require_once 'services/Mail.class.php';

abstract class UserController {

	public static function login () {
		$DB = Flight::DB();
		$userManager = Flight::UserManager();

		$headers = apache_request_headers();
		$request = Flight::request();

		if (isset($headers["username"], $headers["password"])) {
			try {
				$userdata = $userManager->login($headers["username"], $headers["password"]);

				if ($userdata) {
					Flight::json($userdata);
				} else {
					Flight::json(false);
				}
			} catch (Exception $e) {
				Flight::halt(401, "401 - User suspended");
			}
		} else {
			Flight::halt(400, "400 - Bad Request");
		}
	}

	public static function logout () {
		$userManager = Flight::UserManager();

		$userManager->logout();
		echo "";
	}

	public static function registration () {
		$userManager = Flight::UserManager();

		if (!$userManager->getLoginState()) {
			$request = Flight::request();
			$json = $request->data;

			if (!isset($json["GravatarEmail"])) {
				$json["GravatarEmail"] = "";
			}

			
			if (isset($json["Name"], $json["Fullname"], $json["Password"], $json["Email"])) {
				if (!(validation::minLength($json["Name"], 3) && validation::minLength($json["Fullname"], 3) && validation::minLength($json["Password"], 6) && validation::email($json["Email"]) && ($json["GravatarEmail"] == null || validation::email($json["GravatarEmail"])))) {
					Flight::halt(400, "Bad request.");
				}

				try {
					$userManager->createUser($json["Name"], $json["Fullname"], $json["Password"], $json["Email"], $json["GravatarEmail"]);
				} catch (Exception $e) {
					Flight::halt(409, "Conflict. User already exists.");
				}

				// Sending a confirmation mail
				MailService::sendTemplate("Registration complete", "noreply@paperdreamer.org", "Paperdreamer", $json["Email"], $json["Fullname"], "mailTemplates/welcome.html", "mailTemplates/welcome.txt", Array("Fullname" => $json["Fullname"]));

				echo "true";
			} else {
				Flight::halt(400, "Missing information");
			}
		} else {
			Flight::halt(412, "412 - You may not be logged in while registering a new user.");
		}
	}
	
	public static function getUser($userID) {
		$userManager = Flight::UserManager();
		$user = $userManager->getUserData($userID);
		if($user) {
			Flight::json($user);
		} else {
			Flight::halt(400, "400 - Bad Request");
		}
	}
	
	public static function getActiveUsers() {
		$DB = Flight::DB();
		$userManager = Flight::UserManager();
		$allUsers = $userManager->getAllActiveUsers();
		if ($allUsers) {
			Flight::json($allUsers);
		} else {
			Flight::halt(400, "400 - Bad Request");//TODO: BEtter error code?
		}
	}
	
	public static function getSuspendedUsers() {
		$DB = Flight::DB();
		$userManager = Flight::UserManager();
		$allUsers = $userManager->getAllSuspendedUsers();
		if ($allUsers) {
			Flight::json($allUsers);
		} else {
			$allUsers = Array();
			Flight::json($allUsers);
		}
	}
	
	public static function activateUser() {
		$DB = Flight::DB();
		$userManager = Flight::UserManager();
		if (!$userManager->getSession()["isAdmin"])
			Flight::halt(401, "401 Unauthorized - You are not logged in as administrator.");
	
		$request = Flight::request();
		$json = $request->data;
		if (isset($json["Username"])) {
			$userID = $userManager->activateUser($json["Username"]);
			
			if ($userID) {
				// Getting the user's data
				$userdata = $userManager->getUserData($userID);
				// Sending a mail
				MailService::sendTemplate("Account activated", "noreply@paperdreamer.org", "Paperdreamer", $userdata["Email"], $userdata["Fullname"], "mailTemplates/activationComplete.html", "mailTemplates/activationComplete.txt", Array("Fullname" => $userdata["Fullname"], "Username" => $userdata["Name"]));
				Flight::json(true);
			} else {
				Flight::json(false);
			}	
		} else {
			Flight::json(false);
		}
	}
	
	public static function changeRole() {
		$DB = Flight::DB();
		$userManager = Flight::UserManager();
		if(!$userManager->checkAdmin()) {
			Flight::halt(403, "403 - Forbidden Access");
		}
		$request = Flight::request();
		$json = $request->data;
		if(isset($json["UserID"]) && isset($json["Role"])) {
			$success = $userManager->changeRole($json["UserID"],$json["Role"]);
			if($success) {
				Flight::json(true);
			} else {
				Flight::json(false);
			}
		} else {
			Flight::halt(400, "400 - Bad Request");
		}
	}

	public static function getSession () {
		$userManager = Flight::UserManager();

		// Return the result
		Flight::json($userManager->getSession());
	}
}

?>
