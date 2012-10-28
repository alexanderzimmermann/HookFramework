<?php
/**
 * Test helper file for the HookFramework.
 * @category   Tests
 * @package    Main
 * @subpackage Help
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

use Core\Log;

require_once __DIR__ . '/../Core/Autoload.php';

// Required unit test files.
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/Runner/Version.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';

/**
	Set Error Reporting to E_ALL | E_STRICT.
*/

error_reporting(E_ALL | E_STRICT);

/*
	Determine the root directories of the framework.
*/

$sRoot      = dirname(__DIR__) . '/';
$sRootTests = __DIR__ . '/';

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
 * Define Example SVN directory and subversion test binary.
 */

define('TEST_SVN_BIN', __DIR__ . '/_files/bin/');
define('TEST_SVN_EXAMPLE', __DIR__ . '/_files/Example/');
define('TEST_SVN_REPOSITORY', __DIR__ . '/../Repositories/');

/**
	Create Log instance.
*/

$sFile = 'config.ini';
if (false === file_exists($sFile))
{
	$sFile = 'config-dist.ini';
} // if

require_once 'Core/Log.php';

$aCfg = parse_ini_file($sRootTests . $sFile);
$oLog = Log::getInstance();

if (false === isset($aCfg['logfile']))
{
	$aCfg['logfile'] = __DIR__ . '/common-test.log';
} // if

exec('echo > ' . $aCfg['logfile']);

if (false === isset($aCfg['logmode']))
{
	$aCfg['logmode'] = Log::HF_VARDUMP;
} // if

$oLog->setLogFile($aCfg['logfile']);
$oLog->setLogMode($aCfg['logmode']);

// Unset global variables that are no longer needed.
unset($sRoot);
