<?php

class CustomDynamicObjects {

	protected $wpConnector;
	protected $jsons;

	/**
	 * @description Set options
	 */
	public function __construct($connector, $jsons) {
		$this->wpConnector = $connector;
		$this->jsons = $jsons;
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
		foreach ($this->jsons->getObjectTypes() as $objectType) {
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
		$this->wpConnector->add_meta_box(
			'custom_dynamic_objects',
			'Object Type',
			array($this, 'customDynamicObjectsMetaBox'),
			'post',
			'side',
			'high',
			null
		);
	}

	public function createBackend(){
		$this->wpConnector->add_action('add_meta_boxes', array($this, 'addingObjectTypeMetaBox'));
	}

}

?>