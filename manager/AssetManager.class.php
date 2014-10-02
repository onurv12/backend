<?php
	require_once("manager/config.php");

	abstract class AssetManager {

		static function uploadAsset ($tagID, $assetName, $files) {
			$DB = Flight::DB();

			if (isset($files->file)) {
				$fileName = $files->file["name"];
				$tmpFile = $files->file["tmp_name"];

				// TODO: We should genereate a random filename here.

				move_uploaded_file($tmpFile, "assets/$fileName");

				$parameters = array();
				$parameters[":Filename"] = $fileName;
				$parameters[":Name"] = $assetName;

				$statement = $DB->query("INSERT INTO " . ASSET_TABLE . "(Filename, Name, UploaderID, ProjectID, Global) VALUES (:Filename, :Name, 1, 1, 1)", $parameters);
				$assetID = $statement->getLastInsertId();

				self::assignTag($assetID, $tagID);
			}
		}

		static function removeAssetFromCanvas ($projectID, $canvasID, $assetID) {
			$DB = Flight::DB();

			$parameters = array();
			$parameters[":AssetID"] = $assetID;
			$parameters[":CanvasID"] = $canvasID;

			$DB->query("DELETE FROM " .ASSETTOCANVAS_TABLE .  " WHERE ID = :AssetID AND CanvasID = :CanvasID", $parameters);
		}


		static function getAll () {
			// TODO: Private vs. global assets?
			$DB = Flight::DB();

			$tags = array();
			$tags_tmp = self::getAllTags();


			foreach ($tags_tmp as $tag) {
				$tag["Assets"] = self::getAssets($tag["ID"]);
				$tags[] = $tag;
			}

			return $tags;
		}

		// TAGGING METHODS //

		static function getTags ($assetID) {
			$DB = Flight::DB();

			$parameters = array();
			$parameters[":AssetID"] = $assetID;

			return $DB->getList("SELECT `" . TAG_TABLE . "`.ID AS TagID, `" . TAG_TABLE . "`.Name FROM " . TAG_TABLE . ", `" . TAGTOASSET_TABLE . "` WHERE `" . TAG_TABLE . ".ID = `" . TAGTOASSET_TABLE . "`.TagID AND `" . TAGTOASSET_TABLE . "`.AssetID = :AssetID", $parameters);
		}

		static function getAssets ($tagID) {
			$DB = Flight::DB();

			$parameters = array();
			$parameters[":TagID"] = $tagID;

			return $DB->getList("SELECT `" . ASSET_TABLE . "`.* FROM `" . ASSET_TABLE . "`, `" . TAGTOASSET_TABLE . "` WHERE `" . ASSET_TABLE . "`.ID = `" . TAGTOASSET_TABLE . "`.AssetID AND `" . TAGTOASSET_TABLE . "`.TagID = :TagID", $parameters);
		}

		static function getAllTags () {
			$DB = Flight::DB();

			return $DB->getList("SELECT * FROM " . TAG_TABLE);
		}

		static function getAllAssets () {
			$DB = Flight::DB();

			return $DB->getList("SELECT * FROM " . ASSET_TABLE);
		}

		static function assignTag ($assetID, $tagID) {
			$DB = Flight::DB();

			$parameters = array();
			$parameters[":AssetID"] = $assetID;
			$parameters[":TagID"] = $tagID;

			return $DB->query("INSERT INTO `" . TAGTOASSET_TABLE . "`(TagID, AssetID) VALUES (:TagID, :AssetID)", $parameters);
		}

		static function revokeTag ($assetID, $tagID) {
			$DB = Flight::DB();

			$parameters = array();
			$parameters[":AssetID"] = $assetID;
			$parameters[":TagID"] = $tagID;

			return $DB->query("DELETE FROM `" . TAGTOASSET_TABLE . "` WHERE TagID = :TagID AND AssetID = :AssetID", $parameters);
		}

		static function createTag ($name, $creatorUserID) {
			$DB = Flight::DB();

			$parameters = array();
			$parameters[":Name"] = $name;
			$parameters[":CreatorUserID"] = $creatorUserID;

			return $DB->query("INSERT INTO " . TAG_TABLE . "(Name, CreatorUserID) VALUES (:Name, :CreatorUserID)", $parameters);
		}

		static function deleteTag ($tagID) {
			// TODO: Only for admin?
			$DB = Flight::DB();

			$parameters = array();
			$parameters[":TagID"] = $tagID;

			return $DB->query("DELETE FROM " . TAGTOASSET_TABLE ." WHERE ID = :TagID; DELETE FROM `Tag2Asset` WHERE TagID = :TagID", $parameters);
		}

		static function addAssetToCanvas($projectID, $canvasID, $assetID) {
			$DB = Flight::DB();

			$parameters = array();
			$parameters[":CanvasID"] = $canvasID;
			$parameters[":AssetID"] = $assetID;

			return $DB->query("INSERT INTO `" . ASSETTOCANVAS_TABLE . "`(AssetID, CanvasID) VALUES (:AssetID, :CanvasID)", $parameters);
		}
	};

?>