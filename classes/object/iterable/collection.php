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
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Object_Iterable_Collection extends Object_Collection {
	
	public function current()
	{
		return new Object($this->collection->current());
	}

	public function offsetExists($key)
	{
		throw new Exception(
			'Object_Iterable_Collection::offsetExists() not implemented');
	}
	
	public function offsetGet($key)
	{
		throw new Exception(
			'Object_Iterable_Collection::offsetGet() not implemented');
	}

	public function key()
	{
		return $this->collection->key();
	}

	public function next()
	{
		$this->collection->next();
	}

	public function valid()
	{
		return $this->collection->valid();
	}

	public function rewind()
	{
		$this->collection->rewind();
	}

	public function count()
	{
		return $this->collection->count();
	}

}