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
class CollectionIterableTest extends CollectionTest {

	public function testConstruct()
	{
		return new Collection_Iterable(
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
		$this->assertSame(array('id' => 1), $collection[0]);
	}

	/**
	 * @depends  testConstruct
	 * @expectedException  Exception
	 */
	public function testOffsetIsset($collection)
	{
		$this->assertTrue(isset($collection[1]));
	}

}