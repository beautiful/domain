<?php
/**
 * Test the persistence of domain logic
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Registry
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class RegistryTest extends PHPUnit_Framework_TestCase {

	public function testConstruct()
	{
		return new Registry_Mock(
			new Mapper_Array(
				array('table' => 'test'),
				array(
					array('id' => 0, 'name' => 'Luke', 'automobile' => 'Ford Fiesta'),
					array('id' => 1, 'name' => 'Dan', 'automobile' => 'Ford Fiesta'),
					array('id' => 2, 'name' => 'Jack', 'automobile' => 'BMW'),
				)));
	}

	/**
	 * @depends  testConstruct
	 */
	public function testInsert($registry)
	{
		$user = new Model_User;
		$user->name('Bob');
		$registry->persist($user);

		$rows = $registry->mapper()->as_array();
		$this->assertSame(
			$user->__object()->as_array(),
			end($rows));
	}

	/**
	 * @depends  testConstruct
	 */
	public function testFindOne($registry)
	{
		$user = $registry->find_one('Model_User', 2);
		$this->assertInstanceOf('Model_User', $user);
		$this->assertSame('Jack', $user->name());
		return array($registry, $user);
	}

	/**
	 * @depends  testConstruct
	 */
	public function testFindOneReturnsNull($registry)
	{
		$user = $registry->find_one('Model_User', 50);
		$this->assertNull($user);
	}

	/**
	 * @depends  testConstruct
	 */
	public function testFindOneReturnsExistingObject($registry)
	{
		$expected_user = new Model_User;
		$expected_user->name('Francis');
		$registry->persist($expected_user);

		$user = $registry->find_one('Model_User', array('name' => 'Francis'));
		$this->assertSame($expected_user, $user);
	}

	/**
	 * @depends  testFindOne
	 */
	public function testUpdate($registry_user)
	{
		list($registry, $user) = $registry_user;

		$user->name('Jacque');
		$registry->persist($user);

		$row = Arr::get($registry->mapper()->as_array(), 2);
		$this->assertSame($user->__object()->as_array(), $row);
	}

	/**
	 * @depends  testConstruct
	 */
	public function testDeleteClassName($registry)
	{
		$registry->delete('Model_User', 1);
		$this->assertFalse(array_key_exists(1, $registry->mapper()->as_array()));
	}

	/**
	 * @depends  testConstruct
	 */
	public function testDeleteObject($registry)
	{
		$user = $registry->find_one('Model_User', 2);
		$registry->delete($user);
		$this->assertFalse(array_key_exists(2, $registry->mapper()->as_array()));
	}

	/**
	 * @depends            testConstruct
	 * @expectedException  InvalidArgumentException
	 */
	public function testDeleteThrowsBadArgumentException($registry)
	{
		$registry->delete(1);
	}

}