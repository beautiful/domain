<?php
/**
 * Describe the persistence of a data object in documents
 * 
 * In order to persist your data to MongoDB we use
 * [Mapper_Mongo].
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Mapper
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Mapper_Mongo extends Mapper {

	protected $config = array(
		'server' => 'mongodb://localhost',
		'db'     => 'test',
		'collection' => NULL,
		'key'    => '_id',
		'auto'   => TRUE,
		'fields' => array(),
	);

	protected $collection;

	public function __construct(array $config = NULL)
	{
		if ($config !== NULL)
		{
			$this->config = $config + $this->config;
		}
	}

	public function config($key)
	{
		return Arr::get($this->config, $key);
	}

	public function table()
	{
		return $this->config('collection');
	}

	protected function collection()
	{
		if ($this->collection === NULL)
		{
			$connection = new Mongo($this->config('server'));
			$db = $connection->selectDB($this->config('db'));
			$this->collection = $db->selectCollection($this->config('collection'));
		}

		return $this->collection;
	}

	public function as_array()
	{
		return iterator_to_array($this->collection()->find());
	}
	
	public function insert(Object $object)
	{
		$data = $this->extract_data($object);
		$key = $this->config('key');
		
		$this->collection()->insert($data);
		
		$object->{$key} = $data['_id'];
		return $data[$key];
	}

	public function update(Object $object)
	{
		$data = $object->as_array();
		$key = $this->config('key');
		$where = Arr::extract($data, array($key));

		if ($this->collection()->count($where) !== 1)
		{
			throw new Mapper_InvalidKeyException('Key does not exist: '.$data[$key]);
		}

		$this->collection()->update($where, $data);
	}

	public function delete(Object $object)
	{
		$key = $this->config('key');
		$where = Arr::extract($object->as_array(), array($key));

		if ($this->collection()->count($where) !== 1)
		{
			throw new Mapper_InvalidKeyException(
				'Key does not exist: '.$object->{$key});
		}

		$this->collection()->remove($where, array('justOne' => TRUE));
	}

	public function find(array $where = NULL, $limit = NULL)
	{
		if ($where === NULL)
		{
			$cursor = $this->collection()->find();
		}
		else
		{
			$cursor = $this->collection()->find($where);
		}

		if ($limit !== NULL)
		{
			$cursor->limit($limit);
		}

		return new Collection_Object($cursor);
	}

	public function find_one($where)
	{
		if ( ! is_array($where))
		{
			$where = array($this->config('key') => $where);
		}
		
		if ($data = $this->collection()->findOne($where))
		{
			return new Object($data);
		}
	}

}