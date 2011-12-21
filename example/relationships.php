<?php
define('EXT', '.php');
define('APPPATH', __DIR__.'/../test/');
define('SYSPATH', __DIR__.'/../test/system/');

error_reporting(E_ALL | E_STRICT);

require SYSPATH.'classes/kohana/core.php';
require SYSPATH.'classes/kohana.php';

spl_autoload_register(array('Kohana', 'auto_load'));

I18n::lang('en-gb');

Kohana::$config = new Kohana_Config;
Kohana::$config->attach(new Config_File);

Kohana::modules(array('beautiful-domain' => __DIR__.'/../'));

$config = array(
	'server'     => 'mongodb://localhost',
	'db'         => 'test',
	'collection' => 'test',
);
$connection = new Mongo(Arr::get($config, 'server'));
$connection->selectDB(Arr::get($config, 'db'))->drop();

class Model_User extends Domain {
	
	public static function fields()
	{
		return array(
			new Field_Email('email', array(
				'accessor' => TRUE,
			)),
			new Relationship_HasOne('address', array(
				'domain'   => 'Model_Address',
				'accessor' => TRUE,
			)),
			new Relationship_HasMany('cars', array(
				'domain'   => 'Model_Car',
				'accessor' => TRUE,
			)),
		);
	}

}

class Model_Address extends Domain {
	
	public static function fields()
	{
		return array(
			new Field_VarChar('street_one', array(
				'required' => TRUE,
				'accessor' => TRUE,
			)),
			new Field_VarChar('street_two', array(
				'accessor' => TRUE,
			)),
			new Field_VarChar('street_three', array(
				'accessor' => TRUE,
			)),
			new Field_VarChar('town', array(
				'required' => TRUE,
				'accessor' => TRUE,
			)),
			new Field_VarChar('county', array(
				'required' => TRUE,
				'accessor' => TRUE,
			)),
			new Field_VarChar('country', array(
				'default'  => 'United Kingdom',
				'required' => TRUE,
				'accessor' => TRUE,
			)),
		);
	}

}

class Model_Car extends Domain {}

$user = new Model_User;
$user->email('testy@mctest.com');
/*
$address = new Model_Address;
$address->street_one('2 Gold Street');
$address->town('Witham');
$address->county('Essex');

$user->address($address);

var_dump($user);
*/

// Store in mongo
$registry = new Registry(array(
	'Model_User'    => new Mapper_Mongo(array('collection' => 'users')),
	'Model_Address' => new Mapper_Mongo(array('collection' => 'addresses')),
	'Model_Car'     => new Mapper_Mongo(array('collection' => 'cars')),
));
// $registry = new Registry(array(
// 	'Model_User'    => new Mapper_Array(array('table' => 'users')),
// 	'Model_Address' => new Mapper_Array(array('table' => 'addresses')),
// ));
$registry->persist($user);
/*
var_dump($user);

// Get user
$existing_user = $registry->find_one('Model_User', array(
	'email' => 'testy@mctest.com',
));

var_dump($existing_user);
var_dump($user === $existing_user);

var_dump($address === $user->address());
*/
$user->add_car(new Model_Car);

var_dump($user->cars());

exit;

$user->remove_car($user->cars()->current());

var_dump($user->cars());