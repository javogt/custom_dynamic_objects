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

	/**
	 * @description Retruns an array of all object types. Create array if not already exists
	 * @return {Array}
	 */
	private function getObjectTypes() {
		if(empty($this->objectTypes)){
			$this->loadObjects();
		}
		return $this->objectTypes;
	}

	/**
	 * @description Trigger loading of all object jsons
	 */
	private function loadObjects(){
		$cFileList = $this->getObjectJsons();
		$this->loadObjectTypesByNames($cFileList);
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
	 * @description Extract file name from given path
	 * @param  {String} $path
	 * @return {String} name of file
	 */
	private function getFileNameFromPath($path) {
		$pathArr = explode('/', $path);
		return end($pathArr);
	}

	/**
	 * @description Extract backend name from given object data.
	 * @param  {Array} $objectData array of data from config json
	 * @return {String} name to use
	 */
	private function getObjectLabel($objectData) {
		if (array_key_exists('label', $objectData)) {
			return $objectData['label'];
		}
		if (array_key_exists('name', $objectData)) {
			return $objectData['name'];
		}
		return $this->getFileNameFromPath($objectData['file']);
	}

	/**
	 * @description Creates markup for backend meta box
	 * @return {String} html for meta box
	 */
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

	/**
	 * @description Adds meta box to wp backend
	 */
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
