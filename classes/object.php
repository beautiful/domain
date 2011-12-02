<?php
/**
 * Describe a single object of data
 *
 * In order to transfer domain data from a [Domain] to a
 * [Mapper] we use an [Object].
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Object
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Object {

	protected $data = array();

	protected $filters;

	public function __construct(array $values = NULL)
	{
		if ($values !== NULL)
		{
			$this->load_array($values);
		}
	}

	public function load_array(array $values)
	{
		$this->data = $values;
	}

	public function as_array()
	{
		return $this->data;
	}

	public function __get($field)
	{
		if (array_key_exists($field, $this->data))
		{
			return Arr::get($this->data, $field);
		}
	}

	public function __set($field, $value)
	{
		$this->data[$field] = $value;
	}

}
