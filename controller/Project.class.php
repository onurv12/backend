<?php
require_once "../userManagement/CanvasManager.php";

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

	// Gets a certain project respecting the ID
	public static function get($ID) {
		// TODO: Make sure the user is allowed to access this project...!
		$DB = Flight::DB();
		$productionManager = Flight::ProductionManager();
		$canvasManager = new CanvasManager($DB);

		$projectInfo = $productionManager->getProject($ID);

		$panelsTmp = $canvasManager->getPanels($ID);
		$panels = array();

		if (!count($panelsTmp) || !$projectInfo) {
			Flight::halt(404, "This project could not be found.");
		}

		foreach ($panelsTmp as $panel) {
			$panel["Assets"] = $canvasManager->getAssets($panel["ID"]);

			$panels[] =$panel;
		}

		$project = $projectInfo;
		$project["Panels"] = $panels;

		Flight::json($project);
	}


	public static function getCanvas ($ProjectID, $CanvasID) {
		// TODO: Make sure the user is allowed to access this project...!
		$DB = Flight::DB();
		$canvasManager = new CanvasManager($DB);

		$canvas = $canvasManager->getCanvas($ProjectID, $CanvasID);

		if ($canvas) {
			$canvas["Assets"] = $canvasManager->getAssets($CanvasID);
		}

		Flight::json($canvas);
	}
}

?>
