<?php
/**
 * Describe the persistence of a data object
 * 
 * In order to store and retrieve an [Object] from a
 * persistent source we use a [Mapper].
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Mapper
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
abstract class Mapper {
	
	/**
	 * @return  mixed  the key of the newly stored Object
	 */
	abstract public function insert(Object $object);

	/**
	 * @return  void
	 */
	abstract public function update(Object $object);

	/**
	 * @param   Object, or class name
	 * @param   If class name provided this is the primary key
	 * @return  void
	 */
	abstract public function delete(Object $object);

	/**
	 * @return  Cursor
	 */
	abstract public function find($where, $limit = 1);
	
	/**
	 * @return  Object
	 */
	abstract public function find_one($where);

}