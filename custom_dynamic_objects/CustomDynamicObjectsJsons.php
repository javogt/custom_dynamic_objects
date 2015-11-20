<?php

class CustomDynamicObjectsJsons {

	protected $jsonsPath;

	protected $objectTypes;

	public function __construct($jsonsPath){
		$this->jsonsPath = $jsonsPath;
	}

	/**
	 * @description Returns path to object jsons
	 * @return {String}
	 */
	public function getJsonPath() {
		return $this->jsonsPath;
	}

	/**
	 * @description Returns an array with paths to all config jsons
	 * @return {Array}
	 */
	public function getObjectJsons() {
		return array_filter(scandir($this->getJsonPath()), function ($name) {
			return !($name == '.' || $name == '..');
		});
	}
	/**
	 * @description Trigger loading of all object jsons
	 */
	private function loadObjects(){
		$cFileList = $this->getObjectJsons();
		$this->loadObjectTypesByNames($cFileList);
	}

	/**
	 * @description Adds new object type to class object types
	 * @param {Object}
	 */
	private function addObjectType($newObject) {
		$this->objectTypes[] = $newObject;
	}

	/**
	 * @description Load all objects by name from given list
	 * @param  {Array} $names containing list of objects to load
	 */
	public function loadObjectTypesByNames($names) {
		$_path = $this->getJsonPath();
		foreach ($names as $fileName) {
			$_file = $_path . '/' . $fileName;
			$_jsonString = file_get_contents($_file);
			$_jsonData = json_decode($_jsonString, true);
			$_jsonData['file'] = $_file;
			$this->addObjectType($_jsonData);
		}
	}

	/**
	 * @description Retruns an array of all object types. Create array if not already exists
	 * @return {Array}
	 */
	public function getObjectTypes() {
		if(empty($this->objectTypes)){
			$this->loadObjects();
		}
		return $this->objectTypes;
	}

}

?>