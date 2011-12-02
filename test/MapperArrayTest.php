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
class MapperArrayTest extends PHPUnit_Framework_TestCase {

	public function testInstance()
	{
		return new Mapper_Array(
			array(
				'table'  => 'test',
				'fields' => array('name'),
			),
			array(
				array('id' => 0, 'name' => 'Luke'),
				array('id' => 1, 'name' => 'Bob'),
				array('id' => 2, 'name' => 'Peter'),
			));
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
	 * @expectedException  Exception
	 */
	public function testInsertThrowsAutoKeyException($mapper)
	{
		$mapper->insert(new Object(array($mapper->config('key') => 1)));
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

	public function testInsertNonAuto()
	{
		$mapper = new Mapper_Array(
			array(
				'table'  => 'test',
				'fields' => array('name'),
				'auto'   => FALSE,
			));

		$expected = array('name' => 'Luke', 'id' => 0);
		$id = $mapper->insert(new Object($expected));
		$rows = $mapper->as_array();
		$this->assertSame($expected, $rows[$id]);

		return $mapper;
	}

	/**
	 * @depends            testInsertNonAuto
	 * @expectedException  Exception
	 */
	public function testInsertNonAutoRequiresID($mapper)
	{
		$mapper->insert(new Object(array('name' => 'Luke')));
	}

	/**
	 * @depends            testInsertNonAuto
	 * @expectedException  Exception
	 */
	public function testInsertThrowsDuplicateKeyException($mapper)
	{
		$mapper->insert(new Object(array('id' => 0)));
	}

	/**
	 * @depends  testInsertAuto
	 */
	public function testFindWithID($mapper)
	{
		$collection = $mapper->find(0);
		$this->assertInstanceOf('Object_Collection', $collection);

		$object = $collection->current();
		$this->assertSame('Luke', $object->name);

		return $mapper;
	}

	/**
	 * @depends  testInsertAuto
	 */
	public function testFindWithArray($mapper)
	{
		$collection = $mapper->find(array('name' => 'Luke'));
		$this->assertInstanceOf('Object_Collection', $collection);

		$object = $collection->current();
		$this->assertSame('Luke', $object->name);

		return $mapper;
	}

	/**
	 * @depends  testFindWithArray
	 */
	public function testFindNone($mapper)
	{
		$collection = $mapper->find(50);
		$this->assertInstanceOf('Object_Collection', $collection);
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
	public function testUpdateWithID($mapper)
	{
		$object = $mapper->find_one(array('name' => 'David'));
		$object->name = 'Bob';
		$mapper->update($object);

		$row = current($mapper->as_array());
		$this->assertSame('Bob', $row['name']);

		return $mapper;
	}

	/**
	 * @depends            testUpdateWithID
	 * @expectedException  Exception
	 */
	public function testUpdateThrowsDoesNotExistException($mapper)
	{
		$mapper->update(new Object(array('id' => 100, 'name' => 'Bobby')));
	}

	/**
	 * @depends  testFindOneNone
	 */
	public function testDelete($mapper)
	{
		$object = $mapper->find_one(array('name' => 'Bob'));
		$mapper->delete($object);
		$data = current($mapper->as_array());
		$this->assertNotSame('Bob', $data->name);
		return $mapper;
	}

	/**
	 * @depends            testDelete
	 * @expectedException  Exception
	 */
	public function testDeleteThrowsDoesNotExist($mapper)
	{
		$mapper->delete(new Object(array('id' => 200)));
	}

}