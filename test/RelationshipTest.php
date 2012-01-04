<?php
/**
 * Test a has one relationship between domains
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Relationship
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class RelationshipTest extends PHPUnit_Framework_TestCase {

	protected static $class = 'Relationship_Mock';

	public function provideRelationships()
	{
		$data = array();
		$class = static::$class;

		$data[] = array(
			new $class('test', array(
				'accessor' => TRUE,
			)),
			array(
				'name'     => 'test',
				'accessor' => 'test',
				'domain'   => 'test',
			),
		);

		$data[] = array(
			new $class('test', array(
				'accessor' => 'custom',
				'domain'   => 'Model_Test',
			)),
			array(
				'name'     => 'test',
				'accessor' => 'custom',
				'domain'   => 'Model_Test',
			),
		);

		$data[] = array(
			new $class('test_diff', array(
				'accessor' => FALSE,
			)),
			array(
				'name'     => 'test_diff',
				'accessor' => FALSE,
				'domain'   => 'test_diff',
			),
		);

		$data[] = array(
			new $class('test'),
			array(
				'name'     => 'test',
				'accessor' => FALSE,
				'domain'   => 'test',
			),
		);

		return $data;
	}

	/**
	 * @dataProvider  provideRelationships
	 */
	public function testGetAccessor($relationship, $expected)
	{
		$this->assertSame($expected['accessor'], $relationship->accessor());
	}

	/**
	 * @dataProvider  provideRelationships
	 */
	public function testGetName($relationship, $expected)
	{
		$this->assertSame($expected['name'], $relationship->name());
	}

	/**
	 * @dataProvider  provideRelationships
	 */
	public function testGetDomain($relationship, $expected)
	{
		$this->assertSame($expected['domain'], $relationship->domain());
	}

}