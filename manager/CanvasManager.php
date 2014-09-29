<?php
	require_once("manager/config.php");

	class CanvasManager {
		private $DB;

		public function __construct ($DB) {
			$this->DB = $DB;
		}

		function getCanvas ($ProjectID, $CanvasID) {
			$parameters = array();
			$parameters[":ProjectID"] = $ProjectID;
			$parameters[":CanvasID"] = $CanvasID;

			return $this->DB->getRow("SELECT * FROM " . CANVAS_TABLE . " WHERE ProjectID = :ProjectID AND ID = :CanvasID", $parameters);
		}

		function addCanvas ($ProjectID, $PositionIndex, $Title, $Description, $Notes) {
			$parameters = array();
			$parameters["ProjectID"] = $ProjectID;
			$parameters["PositionIndex"] = $PositionIndex;
			$parameters["Title"] = $Title;
			$parameters["Description"] = $Description;
			$parameters["Notes"] = $Notes;

			$this->DB->query("INSERT INTO " . CANVAS_TABLE . "(ProjectID, PositionIndex, Title, Description, Notes) VALUES(:ProjectID, :PositionIndex, :Title, :Description, :Notes)", $parameters);
		}

		function removeCanvas ($ProjectID, $CanvasID) {
			$parameters = array();
			$parameters[":ProjectID"] = $ProjectID;
			$parameters[":CanvasID"] = $CanvasID;

			return $this->DB->query("DELETE FROM " . CANVAS_TABLE . " WHERE ProjectID = :ProjectID AND ID = :CanvasID", $parameters);
		}

		function removeAll ($ProjectID) {
			$parameters = array();
			$parameters[":CanvasID"] = $CanvasID;

			return $this->DB->query("DELETE FROM " . CANVAS_TABLE . " WHERE ProjectID = :ProjectID", $parameters);
		}

		function updatePanel ($ProjectID, $panelData) {
			$parameters = array();
			$parameters[":ProjectID"] 		= $ProjectID;
			$parameters[":ID"] 				= $panelData["ID"];
			$parameters[":Title"] 			= $panelData["Title"];
			$parameters[":Description"] 	= $panelData["Description"];
			$parameters[":Notes"] 			= $panelData["Notes"];
			$parameters[":PositionIndex"] 	= $panelData["PositionIndex"];

			return $this->DB->query("UPDATE " . CANVAS_TABLE . " SET Title = :Title, Description = :Description, Notes = :Notes, PositionIndex = :PositionIndex WHERE ID = :ID AND ProjectID = :ProjectID", $parameters);
		}

		function updateAssets ($CanvasID, $assets) {
			foreach ($assets as $asset) {
				if (isset($asset["ID"])) {
					$parameters = array();
					$parameters[":ID"] = $asset["ID"];
					$parameters[":Index"] = $asset["Index"];
					$parameters[":top"] = round(floatval($asset["top"]), 6);
					$parameters[":left"] = round(floatval($asset["left"]), 6);
					$parameters[":scaleX"] = round(floatval($asset["scaleX"]), 6);
					$parameters[":scaleY"] = round(floatval($asset["scaleY"]), 6);
					$parameters[":flipX"] = (int)$asset["flipX"];
					$parameters[":flipY"] = (int)$asset["flipY"];
					$parameters[":angle"] = $asset["angle"];	

					$this->DB->query("UPDATE " . ASSETTOCANVAS_TABLE . " SET `Index` = :Index, top = :top, `left` = :left, scaleX = :scaleX, scaleY = :scaleY, flipX = :flipX, flipY = :flipY, angle = :angle WHERE ID = :ID", $parameters);	
				} else {
					$parameters = array();
					$parameters[":AssetID"] = $asset["AssetID"];
					$parameters[":CanvasID"] = $CanvasID;
					$parameters[":Index"] = $asset["Index"];
					$parameters[":top"] = round(floatval($asset["top"]), 6);
					$parameters[":left"] = round(floatval($asset["left"]), 6);
					$parameters[":scaleX"] = round(floatval($asset["scaleX"]), 6);
					$parameters[":scaleY"] = round(floatval($asset["scaleY"]), 6);
					$parameters[":flipX"] = (int)$asset["flipX"];
					$parameters[":flipY"] = (int)$asset["flipY"];
					$parameters[":angle"] = $asset["angle"];	

					$this->DB->query("INSERT INTO " . ASSETTOCANVAS_TABLE . " (AssetID, CanvasID, `Index`, top, `left`, scaleX, scaleY, flipX, flipY, angle) VALUES(:AssetID, :CanvasID, :Index, :top, :left, :scaleX, :scaleY, :flipX, :flipY, :angle)", $parameters);
				}
				
			}
			
		}

		function getPanels ($ProjectID) {
			$parameters = array();
			$parameters[":ProjectID"] = $ProjectID;

			return $this->DB->getList("SELECT * FROM " . CANVAS_TABLE . " WHERE ProjectID = :ProjectID ORDER BY PositionIndex ASC", $parameters);
		}

		function getAssets ($CanvasID) {
			$parameters = array();
			$parameters[":CanvasID"] = $CanvasID;

			return $this->DB->getList("SELECT *, " . ASSETTOCANVAS_TABLE . ".ID FROM " . ASSETTOCANVAS_TABLE . " JOIN " . ASSET_TABLE . " ON " . ASSETTOCANVAS_TABLE . ".AssetID = " . ASSET_TABLE . ".ID WHERE " . ASSETTOCANVAS_TABLE . ".CanvasID = :CanvasID ORDER BY " . ASSETTOCANVAS_TABLE . ".Index DESC", $parameters);
		}
	}
?>
