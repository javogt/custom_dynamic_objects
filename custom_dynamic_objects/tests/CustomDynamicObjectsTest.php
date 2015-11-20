<?php

class StupidTest extends \PHPUnit_Framework_TestCase
{
 
	public function testCreateBackendAddsFieldForJsonMock(){
		// $jsonsMock = $this->getMockBuilder('CustomDynamicObjectsJsons')->getMock();
		$wpConnectorMock = $this->getMockBuilder('CustomDynamicObjectsWordpressConnector')->getMock();
	}

}