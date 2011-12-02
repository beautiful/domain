<?php
/**
 * Describe an iterable collection of objects
 * 
 * In order to iterate a result set from [Mapper] we use an
 * [Object_Collection].
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Object_Collection extends Collection {

	public function current()
	{
		return new Object(parent::current());
	}

}