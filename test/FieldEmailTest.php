<?php
/**
 * Test a field of email data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Field
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class FieldEmailTest extends FieldVarCharTest {

	protected $class = 'Field_Email';
	
	protected function expected()
	{
		$expected = parent::expected();
		$expected['rules'][] = array('email');
		return $expected;
	}

}