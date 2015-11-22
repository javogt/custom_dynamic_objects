<?php

class CustomDynamicObjectsTest extends \PHPUnit_Framework_TestCase
{

	protected $customDynamicObjects;

	protected $jsonsMock;
	protected $wpConnectorMock;

	public function setUp(){
		$this->jsonsMock = $this->getMockBuilder('CustomDynamicObjectsJsons')
			->setConstructorArgs(array('test'))
			->setMethods(array('getObjectTypes'))
			->getMock();

		$this->wpConnectorMock = $this->getMockBuilder('CustomDynamicObjectsWordpressConnector')
		 	->setMethods(array('add_action', 'add_meta_box'))
			->getMock();

		$this->customDynamicObjects = new CustomDynamicObjects($this->wpConnectorMock, $this->jsonsMock);

	}
 
	public function testCreateBackendCallsAddActionWithMetaBoxAndHtmlClassMethod(){

		$this->wpConnectorMock->expects($this->once())
            ->method('add_action')
            ->with(
            	$this->equalTo('add_meta_boxes'),
            	$this->equalTo(array($this->customDynamicObjects, 'addingObjectTypeMetaBox'))
        	);
	
		$this->customDynamicObjects->createBackend();	
	}

	public function testAddingObjectTypeMetaBoxCallsAddMetaBox(){

		$this->wpConnectorMock->expects($this->once())
            ->method('add_meta_box')
            ->with(
            	$this->equalTo('custom_dynamic_objects'),
            	$this->equalTo('Object Type'),
            	$this->equalTo(array($this->customDynamicObjects, 'customDynamicObjectsMetaBox')),
            	$this->equalTo('post'),
            	$this->equalTo('side'),
            	$this->equalTo('high'),
            	$this->equalTo(null)

        	);
	
		$this->customDynamicObjects->addingObjectTypeMetaBox();	

	}

	public function testCustomDynamicObjectsMetaBoxReturnsEmptyUlOnNoObject(){

		$this->jsonsMock->expects($this->once())
			->method('getObjectTypes')
			->will($this->returnValue(array()));

		$this->customDynamicObjects->customDynamicObjectsMetaBox();	
	}

	public function testCustomDynamicObjectsMetaBoxWillNotBreakOnNull(){

		$this->jsonsMock->expects($this->once())
			->method('getObjectTypes')
			->will($this->returnValue(null));

		$this->customDynamicObjects->customDynamicObjectsMetaBox();	
	}

}