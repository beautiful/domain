<?php
/**
 * Describe the mock persistence of a data object
 * 
 * In order to test Beautiful Domain a mock [Mapper] is
 * required so that external issues of a persistence layer do
 * not affect tests what so ever. We use [Mapper_Array] in
 * instances when we do not want to test the persistence
 * of data.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Mapper
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Mapper_Array extends Mapper {

	protected $rows = NULL;

	protected $config = array(
		'key'    => 'id',
		'auto'   => TRUE,
		'fields' => array(),
	);

	public function __construct(array $config = NULL, array $rows = array())
	{
		if ($config !== NULL)
		{
			$this->config = $config + $this->config;	
		}
		
		$this->rows = $rows;
	}

	public function as_array()
	{
		return $this->rows;
	}

	public function config($key)
	{
		return Arr::get($this->config, $key);
	}

	public function table()
	{
		return $this->config('table');
	}
	
	public function insert(Object $object)
	{
		$key = $this->config('key');
		$data = $this->extract_data($object);

		if ($this->config('auto'))
		{
			$key_value = count($this->rows);
		}
		else
		{
			$key_value = $data[$key];
		}
		
		if (isset($this->rows[$key_value]))
		{
			throw new Mapper_InvalidKeyException('Key already exists.');
		}
		
		$this->rows[$key_value] = $data;

		// Record key on both array and object
		$this->rows[$key_value][$key] = $key_value;
		$object->{$key} = $key_value;

		return $key_value;
	}

	public function update(Object $object)
	{
		$key = $object->{$this->config('key')};
		$data = $object->as_array();

		if ($fields = $this->config('fields'))
		{
			$data = Arr::extract($data, $fields);
		}

		if ( ! isset($this->rows[$key]))
		{
			throw new Mapper_InvalidKeyException("Key does not exist: {$key}.");
		}

		$this->rows[$key] = $data + $this->rows[$key];
	}

	public function delete(Object $object)
	{
		$key = $object->{$this->config('key')};

		if ( ! isset($this->rows[$key]))
		{
			throw new Mapper_InvalidKeyException('Key does not exist.');
		}

		unset($this->rows[$key]);
	}

	protected function each_where($where, $callback)
	{
		foreach ($this->rows as $_key => $_row)
		{
			foreach ($where as $_field => $_value)
			{
				if ($_row[$_field] !== $_value)
				{
					continue 2;	
				}
			}

			if (call_user_func($callback, $_key) === FALSE)
			{
				break;
			}
		}
	}

	public function find(array $where = NULL, $limit = NULL)
	{
		if ($where === NULL)
		{
			$found = $this->rows;

			if ($limit)
			{
				$found = array_slice($found, 0, $limit);
			}
		}
		else
		{
			$found = array();

			$rows = $this->rows;
			$this->each_where($where, function ($key) use ($rows, & $found, $limit)
			{
				$found[$key] = $rows[$key];

				if (count($found) === $limit)
				{
					return FALSE;
				}
			});
		}

		return new Collection_Object(new Collection(array_values($found)));
	}

	public function find_one($where)
	{
		if ( ! is_array($where))
		{
			$where = array($this->config('key') => $where);
		}
		
		if (($rows = $this->find($where, 1)) AND $rows->valid())
		{
			return $rows->current();
		}
	}

}