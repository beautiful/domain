<?php
/**
 * Describe a field of password data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Field
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Field_Password extends Field_VarChar {
	
	public function filters()
	{
		$filters = parent::filters();
		$filters[] = array('before_save', function ($password)
		{
			return md5($password);
		});
		return $filters;
	}

}