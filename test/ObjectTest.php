<?php
/**
 * Test a single object of data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Object
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class ObjectTest extends PHPUnit_Framework_TestCase {

	protected $data = array(
		'name' => 'James',
		'age'  => 28,
	);
	
	public function testLoadArray()
	{
		$obj = new Object;
		$obj->load_array($this->data);
		return $obj;
	}

	/**
	 * @depends  testLoadArray
	 */
	public function testAsArray($obj)
	{
		$this->assertSame($this->data, $obj->as_array());
	}
	
	public function testConstruct()
	{
		$obj = new Object($this->data);
		$this->assertSame($this->data, $obj->as_array());
	}

	/**
	 * @depends  testLoadArray
	 */
	public function testMagicGet($obj)
	{
		$this->assertSame($this->data['name'], $obj->name);
	}

	/**
	 * @depends  testLoadArray
	 */
	public function testMagicGetNoneExistentField($obj)
	{
		$this->assertNull($obj->does_not_exist);
	}

	/**
	 * @depends  testLoadArray
	 */
	public function testMagicSet($obj)
	{
		$obj->age = 50;
		$data = $obj->as_array();
		$this->assertSame(50, $data['age']);
	}

}