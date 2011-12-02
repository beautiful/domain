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
		$data = $object->as_array();

		if (isset($data[$this->config('key')]))
		{
			throw new Exception('Cannot insert auto key.');
		}

		$this->collection()->insert($data);
		$object->load_array($data);
		return $data[$this->config('key')];
	}

	public function update(Object $object)
	{
		$data = $object->as_array();
		$where = Arr::extract($data, array($this->config('key')));
		$this->collection()->update($where, $data);
	}

	public function delete(Object $object)
	{
		// Safeguard
		$options = array('justOne' => TRUE);

		$where = Arr::extract($object->as_array(), $this->config('key'));
		$this->collection()->remove($where, $options);
	}

	public function find($where, $limit = NULL)
	{
		if ( ! is_array($where))
		{
			$where = array($this->config('key') => $where);
		}

		$cursor = $this->collection()->find($where);

		return new Object_MongoCursor_Collection($cursor);
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