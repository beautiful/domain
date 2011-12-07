<?php
/**
 * Test an iterable domain collection
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @category    Domain
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class CollectionDomainTest extends CollectionTest {

	public function testConstruct()
	{
		return new Collection_Domain(
			new Collection_Object(
				new Collection(array(
					array('id' => 1),
					array('id' => 2),
				))),
			new IdentityMap,
			'Model_User');
	}

	/**
	 * @depends  testConstruct
	 * @expectedException  Exception
	 */
	public function testOffsetGet($collection)
	{
		$this->assertInstanceOf('Model_User', $collection[0]);
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
		$this->assertInstanceOf('Model_User', $collection->current());
		return $collection;
	}

	/**
	 * @depends  testConstruct
	 */
	public function testCurrentReturnsSameObject($collection)
	{
		$this->assertSame($collection->current(), $collection->current());
	}

}