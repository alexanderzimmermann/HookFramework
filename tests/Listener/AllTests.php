<?php
/**
 * Alle Tests fuer die Listener.
 * @category   Tests
 * @package    Listener
 * @subpackage All
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:AllTests.php 74 2008-11-29 21:19:30Z alexander $
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

// Helperdatei.
require_once dirname(__FILE__) . '/../TestHelper.php';
require_once 'Start/AllTests.php';
require_once 'Pre/AllTests.php';
require_once 'Post/AllTests.php';

/**
 * Alle Tests fuer die Listener.
 * @category   Tests
 * @package    Listener
 * @subpackage All
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Listener_AllTests
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
		$oSuite = new PHPUnit_Framework_TestSuite('Hook Framework - Listener');

		$oSuite->addTestSuite(Start_AllTests::suite());
		$oSuite->addTestSuite(Pre_AllTests::suite());
		$oSuite->addTestSuite(Post_AllTests::suite());

		return $oSuite;
	} // function
} // class
