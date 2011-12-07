# Beautiful Domain

More than just an ORM, Beautiful Domain makes your domain
logic the jewel of your application.

## Example

```php
<?php
class Model_User extends Domain {
	
	public static function fields()
	{
		return array(new Field_Email('email', array('accessor' => TRUE)));
	}

}

// Create new user
$user = new Model_User;

// Set e-mail
$user->email('testy@mctest.com');

// Store in mongo
$registry = new Registry(new Mapper_Mongo(array('collection' => 'users')));
$registry->persist($user);

// Get user
$existing_user = $registry->find_one('Model_User', array(
	'email' => 'testy@mctest.com',
));
var_dump($user === $existing_user); // TRUE

// Update
$user->email('newemail.com');
$registry->persist($user);

// Oh and validate
$validation = new Audit($user);
if ( ! $validation->check())
{
	var_dump($validation->errors('user'));
}

// Find all
foreach ($registry->find('Model_User') as $_user)
{
	var_dump($_user->email());
}
```

## Requirements

 - PHP 5.3 +
 - Kohana 3.2 +

## Author

DrPheltRight

## License

MIT
