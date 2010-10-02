<?php
/**
 * Alle Tests fuer den Filter.
 * @category   Tests
 * @package    Main
 * @subpackage Filter
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @version    SVN: $Id:AllTests.php 74 2008-11-29 21:19:30Z alexander $
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'FilterTest.php';
require_once 'ObjectFilterTest.php';

/**
 * Alle Tests fuer den Filter.
 * @category   Tests
 * @package    Main
 * @subpackage Filter
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Filter_AllTests
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
		$oSuite = new PHPUnit_Framework_TestSuite('Hook Framework - Filter');

		$oSuite->addTestSuite('FilterTest');
		$oSuite->addTestSuite('ObjectFilterTest');

		return $oSuite;
	} // function
} // class
