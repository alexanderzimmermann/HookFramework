<?php
/**
 * Test helper file for the HookFramework.
 * @category   Tests
 * @package    Main
 * @subpackage Help
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

// Required unit test files.
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/Runner/Version.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';

/**
	Error Reporting setzen auf E_ALL | E_STRICT.
*/

error_reporting(E_ALL | E_STRICT);

/*
	Determine the root directorie of the framework.
*/

$sRoot      = dirname(dirname(__FILE__)) . '/';
$sRootTests = $sRoot . 'tests/';

/*
	Prepend the Framework Core/ and tests/ directories to the
	include_path. This allows the tests to run out of the box and helps prevent
	loading other copies of the framework code and tests that would supersede
	this copy.
*/

$aPath = array(
		  $sRoot,
		  $sRootTests,
		  get_include_path()
		 );
set_include_path(implode(PATH_SEPARATOR, $aPath));

/**
	Create Log instance.
*/

require_once 'Core/Log.php';

$aCfg = parse_ini_file($sRootTests . 'config.ini');
$oLog = Log::getInstance();

$oLog->setLogFile($aCfg['logfile']);


// Filter for Xdebug.
$oCoverage = PHP_CodeCoverage_Filter::getInstance();
$oCoverage->addDirectoryToBlacklist($sRoot . '/tests', '.php');

// Unset global variables that are no longer needed.
unset($sRoot);
