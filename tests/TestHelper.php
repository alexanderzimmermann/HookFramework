<?php
/**
 * Test Helper Datei fuer das Hook Framework.
 * @category   Tests
 * @package    Main
 * @subpackage Help
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

// Benötigte Unit- Test- Dateien.
require_once 'PHPUnit/Framework.php';
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
	Prepend the Zend Framework library/ and tests/ directories to the
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
	Log Instanz creieren.
*/

require_once 'Core/Log.php';

$aCfg = parse_ini_file($sRootTests . 'config.ini');
$oLog = Log::getInstance();

$oLog->setLogFile($aCfg['logfile']);


// Filter fuer Xdebug.
PHPUnit_Util_Filter::addDirectoryToFilter($sRoot . '/tests');

// Unset global variables that are no longer needed.
unset($sRoot);
