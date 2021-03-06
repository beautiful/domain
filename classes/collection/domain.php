<?php
/**
 * Describe an iterable collection
 * 
 * In order to iterate a set of data we use a [Collection].
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @category    Domain
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Collection_Domain extends Collection_Iterable {

	protected $identities;

	protected $class_name;

	public function __construct($collection, IdentityMap $identities, $class_name)
	{
		parent::__construct($collection);
		$this->identities = $identities;
		$this->class_name = $class_name;
	}

	protected function identities()
	{
		return $this->identities;
	}

	protected function class_name()
	{
		return $this->class_name;
	}

	public function current()
	{
		$object = parent::current();

		if ($domain = $this->identities()->get($object))
		{
			return $domain;
		}

		$class = $this->class_name();
		$domain = new $class;
		$domain->__object($object);

		$this->identities()->set($domain);

		return $domain;
	}

}