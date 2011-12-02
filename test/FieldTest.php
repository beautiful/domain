<?php
/**
 * Test a field of data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Field
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class FieldTest extends PHPUnit_Framework_TestCase {

	protected $class = 'Field_Mock';

	protected function expected()
	{
		return array();
	}
	
	public function providerField()
	{
		$settings = array(
			array(
				'accessor' => 'name',
				'rules'    => array(
					array('not_empty'),
				),
			),
			array(
				'accessor' => 'alt',
				'filters' => array(
					array('before_save', function ($v) { return $v; }),
				),
			),
		);

		$params = array();
		$class = $this->class;

		foreach ($settings as $_k => $_setting)
		{
			$params[] = array(
				new $class('name', $_setting),
				array_merge_recursive($_setting, $this->expected()));
		}

		return $params;
	}

	/**
	 * @dataProvider  providerField
	 */
	public function testName($field)
	{
		$this->assertSame('name', $field->name());
	}

	/**
	 * @dataProvider  providerField
	 */
	public function testRules($field, $settings)
	{
		if (isset($settings['rules']))
		{
			$this->assertSame($settings['rules'], $field->rules());
		}
		else
		{
			$this->assertSame(array(), $field->rules());
		}
	}

	/**
	 * @dataProvider  providerField
	 */
	public function testFilters($field, $settings)
	{
		if (isset($settings['filters']))
		{
			foreach ($field->filters() as $_k => $_filter)
			{
				$this->assertSame($settings['filters'][$_k][0], $_filter[0]);
				$this->assertTrue(is_callable($_filter[1]));
			}
		}
		else
		{
			$this->assertSame(array(), $field->filters());
		}
	}

	/**
	 * @dataProvider  providerField
	 */
	public function testIsAccessor($field, $settings)
	{
		$this->assertSame(
			isset($settings['accessor']),
			$field->is_accessor());
	}

	/**
	 * @dataProvider  providerField
	 */
	public function testAccessor($field, $settings)
	{
		if (isset($settings['accessor']))
		{
			$this->assertSame($settings['accessor'], $field->accessor());
		}
		else
		{
			$this->assertNull($field->accessor());
		}
	}

}