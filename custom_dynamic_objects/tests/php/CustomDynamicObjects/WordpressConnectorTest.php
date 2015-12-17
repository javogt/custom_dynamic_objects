<?php

use CustomDynamicObjects\WordpressConnector;

function add_action(){
	\UnitTesting\FunctionSpy\Spy::add_action();
}

function add_meta_box(){
	\UnitTesting\FunctionSpy\Spy::add_meta_box();
}

class WordpressConnectorTest extends \PHPUnit_Framework_TestCase {

	use \UnitTesting\FunctionSpy\SpyTrait;

	private $wordpressConnector;

	public function setUp(){
		$this->initSpy();
		$this->wordpressConnector = new WordpressConnector();
	}

	public function tearDown()
    {
        $this->flushSpy();
    }

	public function testAddActionCallsWpAddAction(){

		$this->wordpressConnector->add_action('foo', 'bar');

        $this->assertFunctionLastCalledWith('add_action', array('foo', 'bar', null, null));
		
	}

	public function testAddMetaBoxCallsWpAddMetaBox(){

		$this->wordpressConnector->add_meta_box('foo', 'bar', 'baz');

        $this->assertFunctionLastCalledWith('add_meta_box', array('foo', 'bar', 'baz', null, null, null, null));
		
	}

	public function testGetGlobalWpdbReturnsGlobalWpdbObject(){
		$GLOBALS['wpdb'] = 'globalWpdbTest';
		$result = $this->wordpressConnector->getGlobalWpdb();
		$this->assertSame($result, $GLOBALS['wpdb']);
	}

}

