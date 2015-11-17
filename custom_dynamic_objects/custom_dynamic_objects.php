<?php
/**
 * @package Custom Objects
 * @version 0.0.0
 */
/*
Plugin Name: Custom Dynamic Objects
Description: Plugin to creat custom dynamic objects
Author: Jakob Andreas Vogt
Version: 0.0.0
Author URI:
 */ 


class CustomDynamicObjects {

	protected $objectTypeJsons = [];
	protected $objectTypes;
	protected $fileSystem;
	protected $options = [];

	/**
	 * @description Set options
	 */
	public function __construct() {
		$this->options['jsonsPath'] = __DIR__ . '/objects';
	}

	/**
	 * @description Returns path to object jsons
	 * @return {String}
	 */
	public function getJsonPath() {
		return $this->options['jsonsPath'];
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
	 * @description Adds new object type to class object types
	 * @param {Object}
	 */
	private function addObjectType($newObject) {
		$this->objectTypes[] = $newObject;
	}

	private function getObjectTypeJsons() {
		return $this->objectTypeJsons;
	}

	public function setObjectTypeJsons($newValues) {
		$this->objectTypeJsons = $newValues;
	}

	private function getObjectTypes() {
		if(empty($this->objectTypes)){
			$this->loadObjects();
		}
		return $this->objectTypes;
	}

	private function loadObjects(){
		$cFileList = $this->getObjectJsons();
		$this->loadObjectTypesByNames($cFileList);
	}

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

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class CustomDynamicObjectDatabase{

	private $db;

	public function __construct($db){
		$this->db = $db;
	}

	private function wpPrefix(){
		return $this->db->prefix;
	}

	private function query($query){
		if($this->db){
			$this->db->query($query);
		}
	}

	public function tableQueryFromJson($json){

	}

}


class CustomDynamicObjectFileSystem {

	

}

global $wpdb;
// $cdo_Database = new CustomDynamicObjectDatabase($wpdb);
$cdo_FileSystem = new CustomDynamicObjectFileSystem($cdo_config['jsonsPath']);
$cdo_Admin = new CustomDynamicObjects($cdo_FileSystem);


function activate_custom_dynamic_objects() {

};

add_action(
	'activate_custom_dynamic_objects/custom_dynamic_objects.php',
	'activate_custom_dynamic_objects'
);

add_action('add_meta_boxes', array($cdo_Admin, 'addingObjectTypeMetaBox'));

?>
