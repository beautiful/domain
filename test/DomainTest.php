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
			$this->mockObject = new Object(array(
				'name'       => 'Peter',
				'automobile' => 'Ford Fiesta',
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

	public function providerFilterValues()
	{
		$field = current(Model_User::fields());
		$filters = $field->filters();
		$ucwords_filter = $filters[0][1];

		$args = array();

		foreach (array('luke', 'Dan bert', 'Ben', 'jack knows') as $_name)
		{
			$args[] = array($_name, call_user_func($ucwords_filter, $_name));
		}
		
		return $args;
	}

	/**
	 * @depends       testMagicCall
	 * @dataProvider  providerFilterValues
	 */
	public function testSetFilterApplied($name, $expected, $model)
	{
		$model->name($name);
		$this->assertSame($expected, $model->name());
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