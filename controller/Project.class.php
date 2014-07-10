<?php

abstract class ProjectController {
	
	public static function getAllProjects(){
		$productionManager = Flight::productionManager();
		$allProjects = $productionManager->getAllProjects();
		if ($allProjects) {
			Flight::json($allProjects);
		} else {
			$allProjects = Array();
			Flight::json($allProjects);
		}
	}
	
	public static function getBelongedProjects() {
		$productionManager = Flight::productionManager();
		$userManager = Flight::userManager();
		$userID = $userManager->getSession()["ID"];
		$belongedProjects = $productionManager->getBelongedProjects($userID);
		if ($belongedProjects) {
			Flight::json($belongedProjects);
		} else {
			$belongedProjects = Array();
			Flight::json($belongedProjects);
		}
	}
}

?>
