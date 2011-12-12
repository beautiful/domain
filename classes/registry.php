<?php
/**
 * Describe the persistence of domain logic
 *
 * In order to store domain logic or the should I say the
 * results of each logic a [Registry] is used. A [Registry]
 * object uses a [Mapper] and [IdentityMap] to ensure
 * persistence of the [Object] within a [Domain], along with
 * it's integrety.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Registry
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Registry {

	protected $identities = array();

	protected $mapper;

	public function __construct($mapper)
	{
		$this->mapper = $mapper;
	}

	protected function identities($domain = NULL)
	{
		if ($domain instanceOf Domain)
		{
			$domain = get_class($domain);
		}

		if ( ! isset($this->identities[$domain]))
		{
			$this->identities[$domain] = new IdentityMap(
				$this->mapper($domain)->config('key'));
		}

		return $this->identities[$domain];
	}

	protected function mapper($domain = NULL)
	{
		if (is_array($this->mapper))
		{
			if ($domain instanceOf Domain)
			{
				$domain = get_class($domain);
			}

			return $this->mapper[$domain];
		}

		return $this->mapper;
	}

	public function find($class, array $where = NULL)
	{
		$collection = $this->mapper($class)->find($where);
		return new Collection_Domain($collection, $this->identities($class), $class);
	}

	public function find_one($class, $where)
	{
		$mapper = $this->mapper($class);

		if ( ! is_array($where)
			AND $domain = $this->identities($class)->get($where))
		{
			return $domain;
		}

		if (($object = $mapper->find_one($where)) === NULL)
		{
			return $object;
		}

		if ($domain = $this->identities($class)->get(
				$object->{$mapper->config('key')}))
		{
			return $domain;
		}


		$domain = new $class;
		$domain->__object($object);

		$this->find_relations($domain);

		$this->identities($class)->set($domain);

		return $domain;
	}

	protected function find_relations(Domain $domain)
	{
		$mapper = $this->mapper($domain);
		
		foreach ($domain::fields() as $_field)
		{
			if ($_field instanceOf Relationship_HasOne)
			{
				$relation_class = $_field->domain();
				$relation_mapper = $this->mapper($relation_class);
				$relation = new $relation_class;

				$id = $domain->__object()->{$mapper->config('key')};
				$foreign_key = $mapper->table().'_'.$relation_mapper->config('key');

				$domain->__object()->{$_field->name()} =
					$this->find_one($relation_class, array($foreign_key => $id));
			}
		}
	}

	public function persist(Domain $domain)
	{
		$object = $domain->__object();

		if ($this->identities($domain)->has($domain))
		{
			$this->mapper($domain)->update($object);
		}
		else
		{
			$this->mapper($domain)->insert($object);
			$this->identities($domain)->set($domain);
		}

		$this->persist_relations($domain);
	}

	protected function extract_relations(Domain $domain)
	{
		$relations = array();

		foreach ($domain::fields() as $_field)
		{
			if ($_field instanceOf Relationship
				AND $relation = $domain->__object()->{$_field->name()})
			{
				$relations[] = array($_field, $relation);
			}
		}

		return $relations;
	}

	protected function persist_relations(Domain $domain)
	{
		foreach ($this->extract_relations($domain) as $_relation)
		{
			list($field, $relation) = $_relation;

			if ($field instanceOf Relationship_HasOne)
			{
				$mapper = $this->mapper($domain);
				$relation_mapper = $this->mapper($relation);

				$id = $domain->__object()->{$mapper->config('key')};
				$foreign_key = $mapper->table().'_'.$relation_mapper->config('key');
				$relation->__object()->{$foreign_key} = $id;
			}

			$this->persist($relation);
		}
	}

	public function delete($domain, $id = NULL)
	{
		if (is_string($domain))
		{
			$this->delete($this->find_one($domain, $id));
		}
		else if ($domain instanceof Domain)
		{
			$this->mapper()->delete($domain->__object());
			unset($domain);
		}
		else
		{
			throw new InvalidArgumentException(
				'$domain must be string or Domain');
		}
	}

}