<?php

class CustomDynamicObjectsAdmin {

	protected $objectTypeJsons = [];
	protected $objectTypes = [];
	protected $fileSystem;

	public function __construct(CustomDynamicObjectFileSystem $fileSystem) {
		$this->setFileSystem($fileSystem);
		$this->loadObjects();
	}

	// Getter Setter - start

	private function setFileSystem($newValue) {
		$this->fileSystem = $newValue;
	}

	private function getFileSystem(){
		return $this->fileSystem;
	}

	private function getConfigPath() {
		return $this->fileSystem->getConfigPath();
	}

	private function getObjectTypes() {
		return $this->objectTypes;
	}

	private function addObjectType($newObject) {
		$this->objectTypes[] = $newObject;
	}

	private function getObjectTypeJsons() {
		return $this->objectTypeJsons;
	}

	public function setObjectTypeJsons($newValues) {
		$this->objectTypeJsons = $newValues;
	}

	//Getter Setter - end

	private function loadObjects(){
		$cFileList = $this->getFileSystem()->getObjectJsons();
		$this->loadObjectTypesByNames($cFileList);
	}

	public function loadObjectTypesByNames($names) {
		$_path = $this->getConfigPath();
		foreach ($names as $fileName) {
			$_file = $_path . '/' . $fileName;
			$_jsonString = file_get_contents($_file);
			$_jsonData = json_decode($_jsonString, true);
			$_jsonData['file'] = $_file;
			$this->addObjectType($_jsonData);
		}
	}

	private function getFileNameFromPath($path) {
		$pathArr = explode('/', $path);
		return end($pathArr);
	}

	private function getObjectLabel($objectData) {
		if (array_key_exists('label', $objectData)) {
			return $objectData['label'];
		}
		if (array_key_exists('name', $objectData)) {
			return $objectData['name'];
		}
		return $this->getFileNameFromPath($objectData['file']);
	}

	public function customDynamicObjectsMetaBox() {
		$html = '<ul>';
		foreach ($this->getObjectTypes() as $objectType) {
			$html .= '<li>';
			$html .= $this->getObjectLabel($objectType);
			$html .= '</li>';
		}
		$html .= '</ul>';
		echo $html;
	}

	public function addingObjectTypeMetaBox() {
		add_meta_box(
			'custom_dynamic_objects',
			'Object Type',
			array($this, 'customDynamicObjectsMetaBox'),
			'post',
			'side',
			'high',
			null
		);
	}

}

?>