<?php
/**
 * Alle Tests fuer das Hook Framework.
 * @category   Tests
 * @package    Main
 * @subpackage All
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

// Test helper.
require_once 'TestHelper.php';

require_once 'Core/AllTests.php';
require_once 'Core/Filter/AllTests.php';
require_once 'Listener/AllTests.php';

/**
 * Alle Tests fuer das Hook Framework.
 * @category   Tests
 * @package    Main
 * @subpackage All
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class AllTests
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
		$oSuite = new PHPUnit_Framework_TestSuite('Hook Framework');

		$oSuite->addTest(Core_AllTests::suite());
		$oSuite->addTest(Filter_AllTests::suite());
		$oSuite->addTest(Listener_AllTests::suite());

		return $oSuite;
	} // function
} // class
