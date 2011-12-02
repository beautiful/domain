<?php
/**
 * Test a field of password data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Field
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class FieldPasswordTest extends FieldVarCharTest {

	protected $class = 'Field_Password';
	
	protected function expected()
	{
		$expected = parent::expected();
		$expected['filters'][] = array(
			'before_save',
			function ($password)
			{
				return md5($password);
			});
		return $expected;
	}

	public function testPasswordMD5ed()
	{
		$field = new Field_Password('pass');
		$filter = $field->filters();
		$password = 'password';
		
		$this->assertSame(md5($password), call_user_func($filter[0][1], $password));
	}

}