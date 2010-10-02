<?php
/**
 * Alle Tests fuer die Start Listener.
 * @category   Tests
 * @package    Listener
 * @subpackage Start
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Listener/Start/StartTest.php';

/**
 * Alle Tests fuer die Start Listener.
 * @category   Tests
 * @package    Listener
 * @subpackage Start
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Start_AllTests
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
		$sTitle = 'Hook Framework - Post Listener';
		$oSuite = new PHPUnit_Framework_TestSuite($sTitle);

		$oSuite->addTestSuite('StartTest');

		return $oSuite;
	} // function
} // class
