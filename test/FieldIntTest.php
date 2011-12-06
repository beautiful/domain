<?php
/**
 * Test a field of integer data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Field
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class FieldIntTest extends FieldTest {

	protected $class = 'Field_Int';
	
	protected function expected()
	{
		$expected = parent::expected();
		$expected['rules'][] = array('is_int');
		return $expected;
	}

}