<?php

class StupidTest extends \PHPUnit_Framework_TestCase
{
 
	public function testCreateBackendCallsAddActionWithMetyBoxAndHtmlClassMethod(){

		$jsonsMock = $this->getMockBuilder('CustomDynamicObjectsJsons')
			->setConstructorArgs(array('test'))
			->getMock();

		$wpConnectorMock = $this->getMockBuilder('CustomDynamicObjectsWordpressConnector')
		 	->setMethods(array('add_action'))
			->getMock();

		$customDynamicObjects = new CustomDynamicObjects($wpConnectorMock, $jsonsMock);

		$wpConnectorMock->expects($this->once())
            ->method('add_action')
            ->with(
            	$this->equalTo('add_meta_boxes'),
            	$this->equalTo(array($customDynamicObjects, 'addingObjectTypeMetaBox'))
        	);
	
		$customDynamicObjects->createBackend();	
	}

}