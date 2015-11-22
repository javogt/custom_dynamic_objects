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
	 * @description Creates markup for backend meta box
	 * @return {String} html for meta box
	 */
	public function customDynamicObjectsMetaBox() {
		print_r($this->jsons->getObjectTypes());
		$html = '<ul>';
		foreach ($this->jsons->getObjectTypes() as $objectType) {
			$html .= '<li>';
			$html .= $this->jsons->getObjectLabel($objectType);
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

	/**
	 * @description Creating part of backend in wp posts backend
	 */
	public function createBackend(){
		$this->wpConnector->add_action('add_meta_boxes', array($this, 'addingObjectTypeMetaBox'));
	}

}

?>