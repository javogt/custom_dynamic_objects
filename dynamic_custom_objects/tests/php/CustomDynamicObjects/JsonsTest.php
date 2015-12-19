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

		/**
		 * @description Dataprovider for testGetObjectTypesReturnsArrayOfAllJsonFiles
		 */
		public function returnsDirsAndFileArrays(){
			return [
				'one json file' => [['hey.json'], [['file' => 'existing/path/hey.json']]],
				'two json files' => [['hey.json', 'ho.json'], [['file' => 'existing/path/hey.json'],['file' => 'existing/path/ho.json']]],
				'two json files and another' => [['hey.json', 'ho.json', 'nope.txt'], [['file' => 'existing/path/hey.json'],['file' => 'existing/path/ho.json']]],
			];
		}

		/**
		 * @dataProvider returnsDirsAndFileArrays
		 */
		public function testGetObjectTypesReturnsArrayOfAllJsonFiles($dir, $expected){
			global $fileExistsMockReturn, $scandirMockReturn;
			$fileExistsMockReturn = true;
			$scandirMockReturn = $dir;
			$jsons = new Jsons('existing/path');
			$result = $jsons->getObjectTypes();
			$this->assertEquals($expected, $result);
		}

		public function testGetObjectLabelReturnsOnlyPropertyFile(){
			$object = ['file' => 'file'];
			$jsons = new Jsons('');
			$result = $jsons->getObjectLabel($object);
			$this->assertSame('file', $result);
		}

		public function testGetObjectLabelReturnsOnlyPropertyName(){
			$object = ['name' => 'name', 'file' => 'file'];
			$jsons = new Jsons('');
			$result = $jsons->getObjectLabel($object);
			$this->assertSame('name', $result);
		}

		public function testGetObjectLabelReturnsOnlyPropertyLabel(){
			$object = ['label' => 'label', 'name' => 'name', 'file' => 'file'];
			$jsons = new Jsons('');
			$result = $jsons->getObjectLabel($object);
			$this->assertSame('label', $result);
		}

	}

}