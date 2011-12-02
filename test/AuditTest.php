<?php
/**
 * Test the validity of data
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Validation
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class AuditTest extends PHPUnit_Framework_TestCase {

	public function testConstruct()
	{
		return new Audit(new Model_User);
	}

	/**
	 * @depends  testConstruct
	 */
	public function testRulesSet($audit)
	{
		$this->assertTrue($audit->check());
	}

}
