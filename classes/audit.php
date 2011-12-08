<?php
/**
 * Describe the validity of data
 * 
 * In order to ensure data being persisted from a [Domain]
 * object is valid we use [Audit]. This is an extension of the
 * [Validation] object to ensure only a [Domain] object is
 * passed into the constructor.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Validation
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Audit extends Validation {
	
	public function __construct(Domain $domain)
	{
		parent::__construct($domain->__object()->as_array());

		foreach ($domain::fields() as $_field)
		{
			$this->rules($_field->name(), $_field->rules());
		}
	}

}