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
		new Registry_Mock(
			new Mapper_Array(
				NULL,
				array(
					array('id' => 0, 'name' => 'Luke', 'automobile' => 'Ford Fiesta'),
					array('id' => 1, 'name' => 'Dan', 'automobile' => 'Ford Fiesta'),
					array('id' => 2, 'name' => 'Jack', 'automobile' => 'BMW'),
				)));
	}

	public function provideRegistries()
	{
		return array(
			array(new Registry_Mock(
				new Mapper_Array(
					NULL,
					array(
						array('id' => 0, 'name' => 'Luke', 'automobile' => 'Ford Fiesta'),
						array('id' => 1, 'name' => 'Dan', 'automobile' => 'Ford Fiesta'),
						array('id' => 2, 'name' => 'Jack', 'automobile' => 'BMW'),
					)))),
			array(new Registry_Mock(array(
				'Model_User' => new Mapper_Array(
					NULL,
					array(
						array('id' => 0, 'name' => 'Luke', 'automobile' => 'Ford Fiesta'),
						array('id' => 1, 'name' => 'Dan', 'automobile' => 'Ford Fiesta'),
						array('id' => 2, 'name' => 'Jack', 'automobile' => 'BMW'),
					))))));
	}

	/**
	 * @dataProvider  provideRegistries
	 */
	public function testInsert($registry)
	{
		$user = new Model_User;
		$user->name('Bob');
		$user->car('BMW');
		$registry->persist($user);

		$rows = $registry->mapper($user)->as_array();
		$this->assertSame(
			$user->__object()->as_array(),
			end($rows));
	}

	/**
	 * @dataProvider  provideRegistries
	 */
	public function testFind($registry)
	{
		$users = $registry->find('Model_User', array('automobile' => 'Ford Fiesta'));
		$this->assertInstanceOf('Collection_Domain', $users);
		
		$user = $users->current();
		$this->assertInstanceOf('Model_User', $user);
		$this->assertSame('Luke', $user->name());
	}

	/**
	 * @dataProvider  provideRegistries
	 */
	public function testFindReturnsEmptyCollectionDomain($registry)
	{
		$users = $registry->find('Model_User', array('id' => 50));
		$this->assertInstanceOf('Collection_Domain', $users);
		$this->assertSame(0, $users->count());
	}

	/**
	 * @dataProvider  provideRegistries
	 */
	public function testFindOne($registry)
	{
		$user = $registry->find_one('Model_User', 2);
		$this->assertInstanceOf('Model_User', $user);
		$this->assertSame('Jack', $user->name());
	}

	/**
	 * @dataProvider  provideRegistries
	 */
	public function testFindSameObject($registry)
	{
		$this->assertSame(
			$registry->find_one('Model_User', 2),
			$registry->find_one('Model_User', array('name' => 'Jack')));
	}

	/**
	 * @dataProvider  provideRegistries
	 */
	public function testFindSameObjectSameParams($registry)
	{
		$this->assertSame(
			$registry->find_one('Model_User', 2),
			$registry->find_one('Model_User', 2));
	}

	/**
	 * @dataProvider  provideRegistries
	 */
	public function testFindOneReturnsNull($registry)
	{
		$user = $registry->find_one('Model_User', 50);
		$this->assertNull($user);
	}

	/**
	 * @dataProvider  provideRegistries
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
	 * @dataProvider  provideRegistries
	 */
	public function testUpdate($registry)
	{
		$user = $registry->find_one('Model_User', 2);

		$user->name('Jacque');
		$registry->persist($user);

		$row = Arr::get($registry->mapper($user)->as_array(), 2);
		$this->assertSame($user->__object()->as_array(), $row);
	}

	/**
	 * @dataProvider  provideRegistries
	 */
	public function testDeleteClassName($registry)
	{
		$registry->delete('Model_User', 1);
		$this->assertFalse(array_key_exists(1, $registry->mapper('Model_User')->as_array()));
	}

	/**
	 * @dataProvider  provideRegistries
	 */
	public function testDeleteObject($registry)
	{
		$user = $registry->find_one('Model_User', 2);
		$registry->delete($user);
		$this->assertFalse(array_key_exists(2, $registry->mapper($user)->as_array()));
	}

	/**
	 * @dataProvider       provideRegistries
	 * @expectedException  InvalidArgumentException
	 */
	public function testDeleteThrowsBadArgumentException($registry)
	{
		$registry->delete(1);
	}

	/**
	 * @dataProvider  provideRegistries
	 */
	public function testRelationsPersisted($registry)
	{
		$user = $registry->find_one('Model_User', 2);
		$bob = new Model_User;
		$bob->name('Bob');
		$bob->car('Shitmobile');
		$user->friend($bob);
		$registry->persist($user);

		$this->assertTrue(array_key_exists('id', $bob->__object()->as_array()));
	}

}