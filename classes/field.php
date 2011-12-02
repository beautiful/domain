<?php
/**
 * Describe a field of data
 * 
 * In order to describe fields used by a [Domain] we use a
 * [Field] object. A field usually describes it's access via
 * the [Domain], it's [Validation] rules and filters.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Field
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
abstract class Field {

	protected $name;

	protected $accessor;

	protected $rules;

	protected $filters;

	public function __construct($name, array $config = NULL)
	{
		$this->name = $name;
		$this->accessor = Arr::get($config, 'accessor', FALSE);
		$this->rules    = Arr::get($config, 'rules',    array());
		$this->filters  = Arr::get($config, 'filters',  array());
	}

	public function name()
	{
		return $this->name;
	}
	
	public function rules()
	{
		return $this->rules;
	}

	public function filters()
	{
		return $this->filters;
	}

	public function accessor()
	{
		return $this->accessor;
	}

	public function is_accessor()
	{
		return (bool) $this->accessor;
	}

}