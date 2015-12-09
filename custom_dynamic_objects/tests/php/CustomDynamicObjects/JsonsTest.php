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

		public function returnsDirsAndFileArrays(){
			return [
				'one file' => [['hey.json'], [['file' => 'existing/path/hey.json']]]
			];
		}

		/**
		 * @dataProvider returnsDirsAndFileArrays
		 */
		public function testGetObjectTypesReturnsArrayOfAllFiles($dir, $expected){
			global $fileExistsMockReturn, $scandirMockReturn;
			$fileExistsMockReturn = true;
			$scandirMockReturn = $dir;
			$jsons = new Jsons('existing/path');
			$result = $jsons->getObjectTypes();
			$this->assertEquals($expected, $result);
		}

	}

}