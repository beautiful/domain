<?php
/**
 * Test the mock persistence of a data object
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Mapper
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
abstract class MapperTest extends PHPUnit_Framework_TestCase {

	/**
	 * @depends  testInstance
	 */
	public function testTable($mapper)
	{
		$this->assertSame($this->table, $mapper->table());
	}

	/**
	 * @depends  testInstance
	 */
	public function testInsertAuto($mapper)
	{
		$id = $mapper->insert(new Object(array('name' => 'Luke')));

		$rows = $mapper->as_array();
		$row = end($rows);
		$this->assertTrue(isset($row[$mapper->config('key')]));
		$this->assertEquals($id, $row[$mapper->config('key')]);

		return $mapper;
	}

	/**
	 * @depends            testInsertAuto
	 * @expectedException  Mapper_InvalidKeyException
	 */
	public function testInsertThrowsAutoKeyException($mapper)
	{
		$mapper->insert(new Object(array($mapper->config('key') => 1)));
		exit;
	}

	/**
	 * @depends  testInsertAuto
	 */
	public function testInsertOnlySpecifiedFields($mapper)
	{
		$id = $mapper->insert(new Object(
			array('name' => 'Cool', 'test' => 'test')));
		$rows = $mapper->as_array();
		$this->assertFalse(isset($rows['test']));

		return $mapper;
	}

	/**
	 * @depends            testInsertNonAuto
	 * @expectedException  Mapper_InvalidKeyException
	 */
	public function testInsertNonAutoRequiresID($mapper)
	{
		$mapper->insert(new Object(array('name' => 'Luke')));
	}

	/**
	 * @depends            testInsertNonAuto
	 * @expectedException  Mapper_InvalidKeyException
	 */
	public function testInsertThrowsInvalidKey($mapper)
	{
		$mapper->insert(new Object(array('id' => 0)));
	}

	/**
	 * @depends  testInsertAuto
	 */
	public function testFindWithArray($mapper)
	{
		$collection = $mapper->find(array('name' => 'Luke'));
		$this->assertInstanceOf('Collection_Object', $collection);

		$object = $collection->current();
		$this->assertSame('Luke', $object->name);

		return $mapper;
	}

	/**
	 * @depends  testInsertAuto
	 */
	public function testFindAll($mapper)
	{
		$collection = $mapper->find();
		$this->assertInstanceOf('Collection_Object', $collection);
		return $mapper;
	}

	/**
	 * @depends  testInsertAuto
	 */
	public function testFindAllWithLimit($mapper)
	{
		$collection = $mapper->find(NULL, 2);
		$this->assertInstanceOf('Collection_Object', $collection);
	
		$count = 0;
		foreach ($collection as $_domain)
		{
			++$count;
		}
		$this->assertSame(2, $count);

		return $mapper;
	}

	/**
	 * @depends  testFindWithArray
	 */
	public function testFindNone($mapper)
	{
		$collection = $mapper->find(array('name' => 'Zed'));
		$this->assertInstanceOf('Collection_Object', $collection);
		$this->assertSame(0, $collection->count());
		return $mapper;
	}

	/**
	 * @depends  testFindNone
	 */
	public function testFindOneWithID($mapper)
	{
		$this->assertSame('Luke', $mapper->find_one(0)->name);
		return $mapper;
	}

	/**
	 * @depends  testFindNone
	 */
	public function testFindOneWithArray($mapper)
	{
		$this->assertSame('Luke', $mapper->find_one(array('name' => 'Luke'))->name);
		return $mapper;
	}

	/**
	 * @depends  testFindOneWithArray
	 */
	public function testFindOneNone($mapper)
	{
		$this->assertNull($mapper->find_one(99));
		return $mapper;
	}

	/**
	 * @depends  testFindOneNone
	 */
	public function testUpdate($mapper)
	{
		$object = $mapper->find_one(array('name' => 'Luke'));

		$object->name = 'Bob';
		$mapper->update($object);

		$row = current($mapper->as_array());
		$this->assertSame('Bob', $row['name']);

		return $mapper;
	}

	/**
	 * @depends            testUpdate
	 * @expectedException  Mapper_InvalidKeyException
	 */
	public function testUpdateThrowsInvalidKey($mapper)
	{
		$mapper->update(new Object(array($mapper->config('key') => 100, 'name' => 'Bobby')));
	}

	/**
	 * @depends  testUpdate
	 */
	public function testDelete($mapper)
	{
		$object = $mapper->find_one(array('name' => 'Bob'));
		$mapper->delete($object);
		$data = current($mapper->as_array());
		$this->assertNotSame('Bob', $data['name']);
		return $mapper;
	}

	/**
	 * @depends            testDelete
	 * @expectedException  Mapper_InvalidKeyException
	 */
	public function testDeleteThrowsInvalidKey($mapper)
	{
		$mapper->delete(new Object(array($mapper->config('key') => 200)));
	}

}