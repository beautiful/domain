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

	protected $identities;

	protected $mapper;

	public function __construct(Mapper $mapper)
	{
		$this->mapper = $mapper;
	}

	protected function identities()
	{
		if ($this->identities === NULL)
		{
			$this->identities = new IdentityMap(
				$this->mapper()->config('key'));
		}

		return $this->identities;
	}

	protected function mapper()
	{
		return $this->mapper;
	}

	public function find_one($class, $id)
	{
		if ($domain = $this->identities()->get($id))
		{
			return $domain;
		}

		$domain = new $class;
		$domain->__object($this->mapper()->find_one($id));

		$this->identities()->set($domain);

		return $domain;
	}

	public function persist(Domain $domain)
	{
		if ($this->identities()->has($domain))
		{
			$this->mapper()->update($domain->__object());
			return;
		}

		$this->mapper()->insert($domain->__object());

		$this->identities()->set($domain);
	}

	public function delete($domain, $id = NULL)
	{
		if (is_string($domain))
		{
			// Find and delete
			$this->delete($this->find_one($domain, $id));
		}
		else if ($domain instanceof Domain)
		{
			$this->mapper()->delete($domain->__object());
			unset($domain);
		}
		else
		{
			throw new InvalidArgumentException('$domain must be string or Domain');
		}
	}

}