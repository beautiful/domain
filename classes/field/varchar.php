<?php
/**
 * Describe a field of varchar data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Field
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Field_VarChar extends Field {

	protected $length = 150;
	
	public function rules()
	{
		$rules = parent::rules();
		$rules[] = array('max_length', array(':value', $this->length));
		return $rules;
	}

}