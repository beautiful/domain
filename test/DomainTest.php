<?php
/**
 * Test a single business domain
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Domain
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class DomainTest extends PHPUnit_Framework_TestCase {

	protected $mockObject;

	protected function mockObject()
	{
		if ($this->mockObject === NULL)
		{
			$friend = new Model_User;
			$friend->__object(new Object(array(
				'name' => 'Bob',
			)));

			$this->mockObject = new Object(array(
				'name'       => 'Peter',
				'automobile' => 'Ford Fiesta',
				'friend'     => $friend,
			));
		}

		return $this->mockObject;
	}

	public function testDefaultObject()
	{
		$model = new Model_User;
		$this->assertInstanceOf('Object', $model->__object());
	}
	
	public function testSetObject()
	{
		$model = new Model_User;
		$obj = $this->mockObject();
		$model->__object($obj);
		return array($model, $obj);
	}

	/**
	 * @depends  testSetObject
	 */
	public function testGetObject($model_obj)
	{
		list($model, $obj) = $model_obj;
		$this->assertSame($obj, $model->__object());
		return $model;
	}

	/**
	 * @depends  testGetObject
	 */
	public function testMagicCall($model)
	{
		$this->assertSame('Peter', $model->name());
		$this->assertSame('Ford Fiesta', $model->car());
		return $model;
	}

	/**
	 * @depends  testMagicCall
	 */
	public function testGetHasOneRelation($domain)
	{
		$this->assertInstanceOf('Model_User', $domain->friend());
	}

	/**
	 * @expectedException  Exception
	 */
	public function testDomainThrowsExceptionWhenAccessorDoesNotExist()
	{
		$domain = new Model_User;
		$domain->does_not_exist();
	}

}