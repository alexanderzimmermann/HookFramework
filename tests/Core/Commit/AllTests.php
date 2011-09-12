<?php
/**
 * Alle Tests fuer den Commit Bereich.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Core/Commit/Parser/AllTests.php';
require_once 'CommitBaseTest.php';
require_once 'CommitDataTest.php';
require_once 'CommitInfoTest.php';
require_once 'CommitObjectTest.php';
require_once 'CommitParserTest.php';

/**
 * Alle Tests fuer den Commit Bereich.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Interface available since Release 1.0.0
 */
class Commit_AllTests
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
		$oSuite = new PHPUnit_Framework_TestSuite('Hook Framework - Commit');

		$oSuite->addTest(Commit_Parser_AllTests::suite());

		$oSuite->addTestSuite('CommitBaseTest');
		$oSuite->addTestSuite('CommitDataTest');
		$oSuite->addTestSuite('CommitInfoTest');
		$oSuite->addTestSuite('CommitObjectTest');
		$oSuite->addTestSuite('CommitParserTest');

		return $oSuite;
	} // function
} // class
