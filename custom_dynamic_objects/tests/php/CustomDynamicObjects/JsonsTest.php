<?php

use CustomDynamicObjects\Jsons;

namespace CustomDynamicObjects {

	$fileExistsMockReturn = false;
	$scandirMockReturn = [];

	function file_exists($path){
		global $fileExistsMockReturn;
		return $fileExistsMockReturn;
	}

	function scandir($path){
		global $scandirMockReturn;
		return $scandirMockReturn;
	}

	function file_get_contents($path){
		return '';
	};


	class JsonsTest extends \PHPUnit_Framework_TestCase {

		public function setUp(){
			global $fileExistsMockReturn, $scandirMockReturn;
			$fileExistsMockReturn = false;
			$scandirMockReturn = [];
		}

		public function testGetObjectTypesReturnsNullIfDirNotExist(){
			$jsons = new Jsons('test\path\not\exists');
			$result = $jsons->getObjectTypes();
			$this->assertNull($result);
		}

		public function testGetObjectTypesReturnsArrayOfAllFiles(){
			global $fileExistsMockReturn, $scandirMockReturn;
			$fileExistsMockReturn = true;
			$scandirMockReturn = ['hey.json'];
			$jsons = new Jsons('existing\path');
			$result = $jsons->getObjectTypes();
			print_r($result);
		}

	}

}