<?php

class StupidTest extends \PHPUnit_Framework_TestCase
{
 
	public function testCreateBackendAddsFieldForJsonMock(){
		$jsonsMock = $this->getMockBuilder('CustomDynamicObjectsJsons')->setConstructorArgs(array('test'))->getMock();
		$wpConnectorMock = $this->getMockBuilder('CustomDynamicObjectsWordpressConnector')
		 	->setMethods(array('add_action'))
			->getMock();

		$wpConnectorMock->expects($this->once())
            ->method('add_action');

		$customDynamicObjects = new CustomDynamicObjects($wpConnectorMock, $jsonsMock);
		$customDynamicObjects->createBackend();

		
	}

}