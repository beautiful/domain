<?php
/**
 * Test a field of varchar data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Field
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class FieldVarCharTest extends FieldTest {

	protected $class = 'Field_VarChar';
	
	protected function expected()
	{
		$expected = parent::expected();
		$expected['rules'][] = array('max_length', array(':value', 150));
		return $expected;
	}

}