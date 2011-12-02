<?php
/**
 * Test the persistence of a data object in documents
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Mapper
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class MapperMongoTest extends MapperArrayTest {

	public function testInstance()
	{
		return new Mapper_Mongo(array('collection' => 'test'));
	}

	/**
	 * @depends  testInsertNonAuto
	 */
	public function testFindWithID($mapper)
	{
		$this->markTestIncomplete();
		$objects = $mapper->find(new MongoID(''));

		$this->assertTrue(is_array($objects));
		$this->assertSame(
			array('id' => 0, 'name' => 'Luke'),
			$objects[0]->as_array());

		return $mapper;
	}

	/**
	 * @depends  testFindNone
	 */
	public function testFindOneWithID($mapper)
	{
		$this->markTestIncomplete();
		$this->assertSame(
			array('name' => 'Bobby', 'id' => 1),
			$mapper->find_one(1)->as_array());
		return $mapper;
	}

	public function testInsertNonAuto()
	{
		$this->markTestIncomplete();
		$mapper = new Mapper_Mongo(
			array(
				'collection'  => 'test',
				'auto'        => FALSE,
			));

		$expected = array('name' => 'Luke', 'id' => 0);
		$id = $mapper->insert(new Object($expected));
		$rows = $mapper->as_array();
		$this->assertSame($expected, $rows[$id]);

		return $mapper;
	}

}