<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://www.arcavias.com/en/license
 */

class Controller_Frontend_Catalog_FactoryTest extends MW_Unittest_Testcase
{
	private $_object;


	protected function setUp()
	{
	}


	protected function tearDown()
	{
	}


	public function testCreateController()
	{
		$target = 'Controller_Frontend_Catalog_Interface';

		$controller = Controller_Frontend_Catalog_Factory::createController( TestHelper::getContext() );
		$this->assertInstanceOf( $target, $controller );

		$controller = Controller_Frontend_Catalog_Factory::createController( TestHelper::getContext(), 'Default' );
		$this->assertInstanceOf( $target, $controller );
	}


	public function testCreateControllerInvalidImplementation()
	{
		$this->setExpectedException( 'Controller_Frontend_Exception' );
		Controller_Frontend_Catalog_Factory::createController( TestHelper::getContext(), 'Invalid' );
	}


	public function testCreateControllerInvalidName()
	{
		$this->setExpectedException( 'Controller_Frontend_Exception' );
		Controller_Frontend_Catalog_Factory::createController( TestHelper::getContext(), '%^' );
	}


	public function testCreateControllerNotExisting()
	{
		$this->setExpectedException( 'Controller_Frontend_Exception' );
		Controller_Frontend_Catalog_Factory::createController( TestHelper::getContext(), 'notexist' );
	}
}
