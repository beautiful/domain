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

	public function __call($method, $args)
	{
		$object = $this->__object();

		foreach (static::fields() as $_field)
		{
			if (($accessor = $_field->accessor()) !== $method)
			{
				continue;
			}

			$value = Arr::get($args, 0);
 
			if ($_field instanceOf Relationship)
			{
				return $object->relation($_field, $value);
			}

			return $object->accessor($_field->name(), $value);
		}
		
		throw new BadMethodCallException("No method {$method}");
	}

}