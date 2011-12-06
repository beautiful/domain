<?php
/**
 * Describe a field of integer data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Field
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Field_Int extends Field {
	
	public function rules()
	{
		$rules = parent::rules();
		$rules[] = array('is_int');
		return $rules;
	}

}