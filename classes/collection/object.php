<?php
/**
 * Describe an iterable collection of objects
 * 
 * In order to iterate a result set from [Mapper_Mongo] we use
 * an [Object_Iterable_Collection].
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @category    Object
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Collection_Object extends Collection_Iterable {
	
	public function current()
	{
		return new Object(parent::current());
	}

}