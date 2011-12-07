<?php
/**
 * Test an iterable object collection
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @category    Object
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class CollectionObjectTest extends CollectionTest {

	public function testConstruct()
	{
		return new Collection_Object(
			new Collection(array(
				array('id' => 1),
				array('id' => 2),
			)));
	}

	/**
	 * @depends  testConstruct
	 * @expectedException  Exception
	 */
	public function testOffsetGet($collection)
	{
		$this->assertInstanceOf('Object', $collection[0]);
	}

	/**
	 * @depends  testConstruct
	 * @expectedException  Exception
	 */
	public function testOffsetIsset($collection)
	{
		$this->assertTrue(isset($collection[1]));
	}

	/**
	 * @depends  testConstruct
	 */
	public function testCurrent($collection)
	{
		$this->assertInstanceOf('Object', $collection->current());
		return $collection;
	}

}