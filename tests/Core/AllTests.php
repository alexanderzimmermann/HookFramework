<?php
/**
 * Alle Tests fuer den Core.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @version    SVN: $Id:AllTests.php 74 2008-11-29 21:19:30Z alexander $
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../TestHelper.php';

require_once 'ArgumentTest.php';
require_once 'ListenerParserTest.php';
require_once 'HookTest.php';
require_once 'ErrorTest.php';
require_once 'UsageTest.php';

require_once 'Core/Commit/AllTests.php';


/**
 * Alle Tests fuer den Core.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Core_AllTests
{
	/**
	 * Hauptfunktion.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite());
	} // function

	/**
	 * Suite- Methode.
	 * @return PHPUnit_Framework_TestSuite
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function suite()
	{
		$oSuite = new PHPUnit_Framework_TestSuite('Hook Framework - Core');

		$oSuite->addTestSuite('ArgumentTest');
		$oSuite->addTestSuite('ListenerParserTest');
		$oSuite->addTestSuite('HookTest');
		$oSuite->addTestSuite('ErrorTest');
		$oSuite->addTestSuite('UsageTest');

		$oSuite->addTestSuite(Commit_AllTests::suite());

		return $oSuite;
	} // function
} // class
