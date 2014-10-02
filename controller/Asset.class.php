<?php
require_once "manager/AssetManager.class.php";

abstract class AssetController {

	public static function getTags() {
		Flight::json(AssetManager::getAll());
	}

	public static function getAssets() {
		Flight::json(AssetManager::getAllAssets());
	}

	public static function createTag() {
		$request = Flight::request();
		AssetManager::createTag($request->data->Name, 1);
	}

	public static function uploadAsset() {
		$request = Flight::request();
		AssetManager::uploadAsset($request->data->TagID, $request->data->Name, $request->files);
	}

	public static function tagAsset($assetID, $tagID) {
		AssetManager::assignTag($assetID, $tagID);
	}

	public static function addAssetToCanvas($projectID, $canvasID, $assetID) {
		AssetManager::addAssetToCanvas($projectID, $canvasID, $assetID);
	}

	public static function removeAsset($projectID, $canvasID, $assetID) {
		AssetManager::removeAssetFromCanvas($projectID, $canvasID, $assetID);
	}
}

?>