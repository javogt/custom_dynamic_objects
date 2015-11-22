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

	public function testAddingObjectTypeMetaBoxCallsAddMetaBox(){

		$jsonsMock = $this->getMockBuilder('CustomDynamicObjectsJsons')
			->setConstructorArgs(array('test'))
			->getMock();

		$wpConnectorMock = $this->getMockBuilder('CustomDynamicObjectsWordpressConnector')
		 	->setMethods(array('add_meta_box'))
			->getMock();

		$customDynamicObjects = new CustomDynamicObjects($wpConnectorMock, $jsonsMock);

		$wpConnectorMock->expects($this->once())
            ->method('add_meta_box')
            ->with(
            	$this->equalTo('custom_dynamic_objects'),
            	$this->equalTo('Object Type'),
            	$this->equalTo(array($customDynamicObjects, 'customDynamicObjectsMetaBox')),
            	$this->equalTo('post'),
            	$this->equalTo('side'),
            	$this->equalTo('high'),
            	$this->equalTo(null)

        	);
	
		$customDynamicObjects->addingObjectTypeMetaBox();	

	}

}