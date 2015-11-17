<?php
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

	};

}