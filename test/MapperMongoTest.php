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

	protected static function config()
	{
		return array(
			'server'     => 'mongodb://localhost',
			'db'         => 'test',
			'collection' => 'test',
		);
	}

	public static function setUpBeforeClass()
	{
		$config = static::config();
		$connection = new Mongo(Arr::get($config, 'server'));
		$connection->selectDB(Arr::get($config, 'db'))->drop();
	}


	public function testInstance()
	{
		return new Mapper_Mongo(static::config());
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

	public function testInsertNonAuto()
	{
		$mapper = new Mapper_Mongo(
			array(
				'collection'  => 'test',
				'auto'        => FALSE,
			));

		$expected = array('_id' => 0, 'name' => 'Luke');
		$id = $mapper->insert(new Object($expected));
		$rows = $mapper->as_array();
		$this->assertSame($expected, $rows[$id]);

		return $mapper;
	}

}