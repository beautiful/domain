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
class Collection_Object_Relation extends Collection_Object {
	
	/**
	 * Enforcing array so that we can easily add and remove
	 * relations in this Collection.
	 */
	public function __construct(array $collection)
	{
		parent::__construct($collection);
	}

	public function add(Domain $domain)
	{
		array_push($this->collection[$field], $domain);
	}

	public function remove(Domain $domain)
	{
		$key = array_search($domain, $this->data[$field]);
		unset($this->collection[$field][$key]);
	}

}