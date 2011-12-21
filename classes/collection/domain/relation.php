<?php
/**
 * Describe an iterable related collection
 * 
 * In order to iterate a set of data we use a [Collection].
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @category    Relationship
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Collection_Domain_Relation extends Collection_Domain {
	
	public function __construct(
		Collection_Object_Relation $collection,
		IdentityMap $identities,
		$class_name)
	{
		parent::__construct($collection, $identities, $class_name);
	}

	public function add(Domain $domain)
	{
		//$this->collection()->add()
	}

	public function remove(Domain $domain)
	{
		
	}

}