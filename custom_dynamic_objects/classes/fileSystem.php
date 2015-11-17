<?php

class CustomDynamicObjectFileSystem {

	protected $configPath;

	public function __construct($jsonsPath) {
		$this->setConfigPath($jsonsPath);
	}

	public function setConfigPath($newValue) {
		$this->configPath = $newValue;
	}

	public function getConfigPath() {
		return $this->configPath;
	}

	public function getObjectJsons() {
		return array_filter(scandir($this->getConfigPath()), function ($name) {
			return !($name == '.' || $name == '..');
		});
	}

}

?>