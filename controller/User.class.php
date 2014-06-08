<?php

abstract class UserController {

	public static function login () {
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
	}

	public static function logout () {
		$userManager = Flight::userManager();

		$userManager->logout();
		echo "";
	}

	public static function registration () {
		$userManager = Flight::userManager();

		if (!$userManager->getLoginState()) {
			$request = Flight::request();
			$json = json_decode($request->body, true);

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

				echo "true";
			} else {
				Flight::halt(400, "Missing information");
			}
		} else {
			Flight::halt(412, "412 - You may not be logged in while registering a new user.");
		}
	}
}

?>