<?php

require '../userManagement/ProjectPermission.class.php';

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
		$userID = $userManager->getSession()["ID"];
		$request = Flight::request();
		$projectID = $request->data->ProjectID;
		$action = $request->data->Action;
		
		if(!$userManager->checkAdmin() && ProjectPermission::getProjectRole($userID, $projectID) != "Director") {
			Flight::halt(403, "403 - Forbidden Access");
		}
		if(isset($projectID) && isset($action)) {
			switch($action) {
				case "open":
					$success = $productionManager->openProject($projectID);
					break;
				case "close":
					$success = $productionManager->closeProject($projectID);
					break;
			}
			if($success) {
				Flight:json(true);
			} else {
				Flight::halt(403, "403 - Forbidden");
			}
		}
	}
	
	public static function deleteProject($projectID) {
		$productionManager = Flight::ProductionManager();
		$userManager = Flight::UserManager();
		if(!$userManager->checkAdmin()) {
			Flight::halt(403, "403 - Forbidden Access");
		}
		if(isset($projectID)) {
			$success = $productionManager->deleteProject($projectID);	
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
