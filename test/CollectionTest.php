<?php
/**
 * Test an iterable collection
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class CollectionTest extends PHPUnit_Framework_TestCase {

	protected $first;
	protected $second;

	public function testConstruct()
	{
		return new Collection(array(
			new Object,
			new Object,
		));
	}

	/**
	 * @depends  testConstruct
	 */
	public function testOffsetGet($collection)
	{
		$this->assertInstanceOf('Object', $collection[0]);
	}

	/**
	 * @depends  testConstruct
	 */
	public function testOffsetIsset($collection)
	{
		$this->assertTrue(isset($collection[1]));
	}

	/**
	 * @depends            testConstruct
	 * @expectedException  Exception
	 */
	public function testOffsetSet($collection)
	{
		$collection[2] = new Object;
	}

	/**
	 * @depends            testConstruct
	 * @expectedException  Exception
	 */
	public function testOffsetUnset($collection)
	{
		unset($collection[1]);
	}

	/**
	 * @depends  testConstruct
	 */
	public function testCurrent($collection)
	{
		$this->assertInstanceOf('Object', $collection->current());
		return $collection;
	}

	/**
	 * @depends  testCurrent
	 */
	public function testKey($collection)
	{
		$this->assertSame(0, $collection->key());
		return $collection;
	}

	/**
	 * @depends  testKey
	 */
	public function testNext($collection)
	{
		$collection->next();
		$this->assertSame(1, $collection->key());
		return $collection;
	}

	/**
	 * @depends  testNext
	 */
	public function testValid($collection)
	{
		$this->assertTrue($collection->valid());
		$collection->next();
		$this->assertFalse($collection->valid());
		return $collection;
	}

	/**
	 * @depends  testValid
	 */
	public function testRewind($collection)
	{
		$collection->rewind();
		$this->assertSame(0, $collection->key());
		return $collection;
	}

	/**
	 * @depends  testRewind
	 */
	public function testCount($collection)
	{
		$this->assertSame(2, $collection->count());
		return $collection;
	}

}