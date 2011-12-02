<?php
/**
 * Describe the identities handled in a session
 * 
 * In order to ensure only one [Domain] object is created per
 * record (row, document, etc.) we use an [IdentityMap] to
 * register [Domain] objects and check for their existence.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    IdentityMap
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class IdentityMap {

	protected $identities = array();

	protected function extract_identity(Domain $domain)
	{
		$id = $domain->__object()->id;
		return $id;
	}

	public function has(Domain $domain)
	{
		return in_array($domain, $this->identities);
	}
	
	public function get($id)
	{
		return Arr::get($this->identities, (string) $id);
	}

	public function set(Domain $domain)
	{
		$id = $this->extract_identity($domain);
		$this->identities[(string) $id] = $domain;
	}

}