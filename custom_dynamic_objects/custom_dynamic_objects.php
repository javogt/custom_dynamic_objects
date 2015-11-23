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

require_once('vendor/autoload.php');

use CustomDynamicObjects\WordpressConnector as WordpressConnector;
use CustomDynamicObjects\Jsons as Jsons;

$customDynamicObjects = new CustomDynamicObjects(
		new WordpressConnector(), 
		new Jsons(__DIR__ . '/objects')
	);

$customDynamicObjects->createBackend();

// global $wpdb;

// require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

// class CustomDynamicObjectDatabase{

// 	private $db;

// 	public function __construct($db){
// 		$this->db = $db;
// 	}

// 	private function wpPrefix(){
// 		return $this->db->prefix;
// 	}

// 	private function query($query){
// 		if($this->db){
// 			$this->db->query($query);
// 		}
// 	}

// 	public function tableQueryFromJson($json){

// 	}

// }

?>
