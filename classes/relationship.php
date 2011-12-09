<?php
/**
 * Describe a relationship between domains
 * 
 * In order to describe [Domain] relationships we use
 * derivatives of [Relationship] to describe the connection.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Relationship
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
abstract class Relationship {

	protected $name;
	
	protected $domain;

	protected $accessor;
	
	public function __construct($name, array $config = NULL)
	{
		$this->name = $name;

		$this->domain = Arr::get($config, 'domain', $name);

		if (($accessor = Arr::get($config, 'accessor', FALSE)) === TRUE)
		{
			$accessor = $name;
		}
		$this->accessor = $accessor;
	}

	public function name()
	{
		return $this->name;
	}

	public function domain()
	{
		return $this->domain;
	}

	public function accessor()
	{
		return $this->accessor;
	}

}