<?php
/**
 * Describe a field of email data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Field
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Field_Email extends Field_VarChar {
	
	public function rules()
	{
		$rules = parent::rules();
		$rules[] = array('email');
		return $rules;
	}

}