<?php
/**
 * Describe a single business domain
 *
 * In order to represent the needs of your users, colleagues,
 * bosses, fans or whoever use [Domain] as a basis for that.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Domain
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Domain {
	
	protected $object;

	public function __object($object = NULL)
	{
		if ($object === NULL)
		{
			if ($this->object === NULL)
			{
				$this->object = new Object;
			}

			return $this->object;
		}

		$this->object = $object;
	}

	protected function __apply_filters($filters, $filter, $value)
	{
		foreach ($filters as $_filter)
		{
			if ($_filter[0] === $filter)
			{
				$value = call_user_func($_filter[1], $value);	
			}
		}

		return $value;
	}

	public function __call($method, $args)
	{
		$object = $this->__object();

		foreach (static::fields() as $_field)
		{
			$field_name = $_field->name();

			if (($accessor = $_field->accessor()) === TRUE)
			{
				$accessor = $field_name;
			}

			if ($accessor !== $method)
			{
				continue;
			}

			$filters = $_field->filters();

			if ($value = Arr::get($args, 0))
			{
				$object->{$field_name} = 
					$this->__apply_filters($filters, 'set', $value);

				return $this;
			}

			return $this->__apply_filters($filters, 'get', $object->{$field_name});
		}
		
		throw new BadMethodCallException("No method {$method}");
	}

}