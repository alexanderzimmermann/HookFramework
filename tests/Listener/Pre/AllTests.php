<?php
/**
 * Alle Tests fuer die Pre Listener.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:AllTests.php 74 2008-11-29 21:19:30Z alexander $
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Listener/Pre/MessageTest.php';
require_once 'Listener/Pre/MessageStrictTest.php';
require_once 'Listener/Pre/IdTest.php';
require_once 'Listener/Pre/StyleTest.php';
require_once 'Listener/Pre/SyntaxTest.php';

/**
 * Alle Tests fuer die Pre Listener.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Pre_AllTests
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
		$sTitle = 'Hook Framework - Pre Listener';
		$oSuite = new PHPUnit_Framework_TestSuite($sTitle);

		$oSuite->addTestSuite('MessageTest');
		$oSuite->addTestSuite('MessageStrictTest');
		$oSuite->addTestSuite('IdTest');
		$oSuite->addTestSuite('StyleTest');
		$oSuite->addTestSuite('SyntaxTest');

		return $oSuite;
	} // function
} // class
