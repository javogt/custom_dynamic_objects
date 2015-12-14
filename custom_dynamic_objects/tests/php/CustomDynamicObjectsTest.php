<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CustomDynamicObjectsTest extends \PHPUnit_Framework_TestCase
{

	protected $customDynamicObjects;

	protected $jsonsMock;
	protected $wpConnectorMock;
	protected $capsuleMock;

	protected $builderMock;

	public function setUp(){
		$this->jsonsMock = $this->getMockBuilder('CustomDynamicObjects\Jsons')
			->setConstructorArgs(['test'])
			->setMethods(['getObjectTypes'])
			->getMock();

		$this->wpConnectorMock = $this->getMockBuilder('CustomDynamicObjects\WordpressConnector')
		 	->setMethods(['add_action', 'add_meta_box'])
			->getMock();

		$this->capsuleMock = $this->getMockBuilder('Capsule')
			->setMethods(['schema'])
			->getMock();

		$this->builderMock = $this->getMockBuilder('Illuminate\Database\Schema\Builder')
			->disableOriginalConstructor()
			->setMethods(['create'])
			->getMock();

		$this->customDynamicObjects = new CustomDynamicObjects($this->wpConnectorMock, $this->jsonsMock, $this->capsuleMock);

	}
 
	public function testCreateBackendCallsAddActionWithMetaBoxAndHtmlClassMethod(){

		$this->wpConnectorMock->expects($this->once())
            ->method('add_action')
            ->with(
            	$this->equalTo('add_meta_boxes'),
            	$this->equalTo([$this->customDynamicObjects, 'addingObjectTypeMetaBox'])
        	);
	
		$this->customDynamicObjects->createBackend();	
	}

	public function testAddingObjectTypeMetaBoxCallsAddMetaBox(){

		$this->wpConnectorMock->expects($this->once())
            ->method('add_meta_box')
            ->with(
            	$this->equalTo('custom_dynamic_objects'),
            	$this->equalTo('Object Type'),
            	$this->equalTo([$this->customDynamicObjects, 'customDynamicObjectsMetaBox']),
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

	public function testCustomDynamicObjectsMetaBoxCreatesHtmlListOfObjects(){

		$this->jsonsMock->expects($this->once())
			->method('getObjectTypes')
			->will(
				$this->returnValue(
					[
						array('file' => 'media.json'), 
						array('file' => 'foo.json'), 
						array('file' => 'test.json')
					]
				)
			);

		$this->expectOutputString('<ul><li>media</li><li>foo</li><li>test</li></ul>');

		$this->customDynamicObjects->customDynamicObjectsMetaBox();	
	}

	public function testMigrateTriggersCapsuleSchemaCreateWithRightTableName(){

		$this->jsonsMock->expects($this->once())
			->method('getObjectTypes')
			->will(
				$this->returnValue(
					[
						[
							'file' => 'media.json',
							'properties' => [

							]
						],
						[
							'file' => 'test.json',
							'properties' => [

							]
						]
					]
				)
			);

        $this->builderMock->expects($this->at(0))
        	->method('create')
    	 	->with(
        		$this->equalTo('customDynamicObjects_media')
    		)
        	->will($this->returnValue(null));

        $this->builderMock->expects($this->at(1))
        	->method('create')
    	 	->with(
        		$this->equalTo('customDynamicObjects_test')
    		)
        	->will($this->returnValue(null));

		$this->capsuleMock->expects($this->atLeastOnce())
              ->method('schema')
              ->will($this->returnValue($this->builderMock));

		$this->customDynamicObjects->migrate();
	}

	public function testMigrateTriggersSchemasCreateFunctionWithRightCallbackFunction(){

		

	}

}