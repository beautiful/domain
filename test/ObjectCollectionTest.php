<?php
/**
 * Test an iterable collection
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @category    Object
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class ObjectCollectionTest extends PHPUnit_Framework_TestCase {

	public function testConstruct()
	{
		return new Object_Collection(array(
			array('id' => 1),
			array('id' => 2),
		));
	}

}