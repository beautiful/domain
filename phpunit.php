<?php
/**
 * Test bootstrap for Beautiful Domain
 *
 * You will find a number of Mock (but real) objects used in
 * various tests. In the future I plan to place these in their
 * own directory.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
define('EXT', '.php');
define('APPPATH', __DIR__.'/test/');
define('SYSPATH', __DIR__.'/test/system/');

error_reporting(E_ALL | E_STRICT);

require SYSPATH.'classes/kohana/core.php';
require SYSPATH.'classes/kohana.php';

spl_autoload_register(array('Kohana', 'auto_load'));

I18n::lang('en-gb');

Kohana::modules(array('beautiful-domain' => __DIR__.'/'));

class Field_Mock extends Field {}

class Model_User extends Domain {
	
	public static function fields()
	{
		return array(
			new Field_Mock('name', array(
				'accessor' => TRUE,
				'filters'  => array(
					array('set', 'ucwords'),
				),
			)),
			new Field_Mock('automobile', array('accessor' => 'car')),
			new Relationship_HasOne('friend', array(
				'accessor' => TRUE,
				'domain'   => 'Model_User',
			)),
		);
	}

}

class Registry_Mock extends Registry {
	
	// Making ::mapper() public for introspection
	public function mapper($class = NULL)
	{
		return parent::mapper($class);
	}

}