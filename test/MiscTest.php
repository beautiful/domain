<?php
/**
 * Extra tests that cross various abstraction layers
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class MiscTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->registry = new Registry(
			new Mapper_Array(
					array(
						'table'  => 'users',
						'fields' => array('name'),
					),
					array(
						array('id' => 0, 'name' => 'Luke'),
						array('id' => 1, 'name' => 'Peter'),
					)
				));
	}

	public function testFindModelAlwaysReturnsSameModel()
	{
		$this->assertSame(
			$this->registry->find('Model_User', array('id' => 0))->current(),
			$this->registry->find('Model_User', array('id' => 0))->current());
	}

	public function testFindOneModelAlwaysReturnsSameModel()
	{
		$this->assertSame(
			$this->registry->find_one('Model_User', array('id' => 0)),
			$this->registry->find_one('Model_User', array('id' => 0)));
	}

	public function testFindModelIsSameAsFindOneModel()
	{
		$collection = $this->registry->find('Model_User', array('id' => 0));
		$model = $this->registry->find_one('Model_User', array('id' => 0));

		$this->assertSame($model, $collection->current());
	}

}