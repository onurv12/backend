<?php

abstract class ProjectController {
	
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
	
	public static function editProject() {
		$productionManager = Flight::ProductionManager();
		$userManager = Flight::UserManager();
		if(!$userManager->checkAdmin()) {
			Flight::halt(403, "403 - Forbidden Access");
		}
		$request = Flight::request();
		if(isset($request->data->ProjectID) && isset($request->data->Action)) {
			switch($request->data->Action) {
				case "open":
					$success = $productionManager->openProject($request->data->ProjectID);
					break;
				case "close":
					$success = $productionManager->closeProject($request->data->ProjectID);
					break;
			}
			if($success) {
				Flight:json(true);
			} else {
				Flight::halt(403, "403 - Forbidden");
			}
		}
	}
	
	public static function deleteProject() {
		$productionManager = Flight::ProductionManager();
		$userManager = Flight::UserManager();
		if(!$userManager->checkAdmin()) {
			Flight::halt(403, "403 - Forbidden Access");
		}
		$request = Flight::request();
		if(isset($request->data->ProjectID)) {
			$success = $productionManager->deleteProject($request->data->ProjectID);	
			if($success) {
				Flight::json(true);
			} else {
				Flight::halt(403, "403 - Forbidden");
			}
		} else {
			Flight::halt(400, "400 - Bad Request");
		}
	}
}

?>
