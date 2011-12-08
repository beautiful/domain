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

	protected function extract_data(Object $object)
	{
		$auto = $this->config('auto');
		$key = $this->config('key');

		if ($auto AND isset($object->{$key}))
		{
			throw new Mapper_InvalidKeyException('Cannot insert auto key.');
		}
		else if ( ! $auto AND ! isset($object->{$key}))
		{
			throw new Mapper_InvalidKeyException('No key inserted.');
		}

		$data = array();

		foreach ($object->as_array() as $_field => $_value)
		{
			if ( ! $_value instanceOf Domain)
			{
				$data[$_field] = $_value;
			}
		}

		if ($fields = $this->config('fields'))
		{
			if ( ! $auto)
			{
				$fields[] = $key;
			}

			$data = Arr::extract($data, $fields);
		}

		return $data;
	}

	abstract public function table();
	
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
	 * Find all records or where 'field' => 'value'. Can have
	 * a limit as the second parameter however this defaults
	 * to NULL.
	 *
	 * @return  Collection_Object
	 */
	abstract public function find(array $where = NULL, $limit = NULL);
	
	/**
	 * Find one record using ID or where 'field' => 'value'
	 *
	 * @return  Object
	 */
	abstract public function find_one($where);

}