<?php

abstract class ProjectController {

	public static function createProject() {
		$DB = Flight::DB();
		$productionManager = Flight::ProductionManager();
		$userManager = Flight::UserManager();
		$request = Flight::request();
		
		if (!$userManager->getSession())
			Flight::halt(403, "Please login!");
		
		if (isset($request->data->Name) && isset($request->data->Director) && $userManager->getUserID($request->data->Director)) {
			if (!$userManager->checkAdmin() && $request->data->Director != $userManager->getSession()["Name"]) {
				Flight::halt(403, "You are not logged in as admin nor are you the director. For that reason you cannot create the project.");
			}
			$projectId = $productionManager->createProject($request->data->Name, $request->data->Description, $userManager->getUserID($request->data->Director));
			if ($projectId) {
				$supervisors = $request->data->supervisors;
				$artists = $request->data->artists;
				$count = count($supervisors);
				for ($i = 0; $i < $count; $i++) {
					$userId = $userManager->getUserID($supervisors[$i]["Name"]);
					if (!$userId || $supervisors[$i]["Name"] == $request->data->Director)
						continue;
					$productionManager->addUser2Project($userId, $projectId, "Supervisor");
				}
				$count = count($artists);
				for ($i = 0; $i < $count; $i++) {
					$userId = $userManager->getUserID($artists[$i]["Name"]);
					if (!$userId || in_array($artists[$i]["Name"], $supervisors) || $artists[$i]["Name"] == $request->data->Director)
						continue;
					$productionManager->addUser2Project($userId, $projectId, "Artist");
				}
				
				Flight::json(true);
			} else {
				Flight::halt(409, "Project name already exists!");
			}
		} else {
			Flight::halt(403, "Please specify at least name and director");
		}
	}
	
	public static function getAllProjects(){
		$productionManager = Flight::ProductionManager();
		$allProjects = $productionManager->getAllProjects();
		if(!$allProjects) {
			$allProjects = Array();
		}
		Flight::json($allProjects);
	}
	
	public static function getBelongedProjects() {
		$productionManager = Flight::ProductionManager();
		$userManager = Flight::UserManager();
		$userID = $userManager->getSession()["ID"];
		$belongedProjects = $productionManager->getBelongedProjects($userID);
		if(!$belongedProjects) {
			$belongedProjects = Array();
		}
		Flight::json($belongedProjects);
	}
}

?>
