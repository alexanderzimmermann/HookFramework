<?php
/**
 * Test helper file for the HookFramework.
 * @category   Tests
 * @package    Main
 * @subpackage Help
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

use Hook\Core\Log;

require_once __DIR__ . '/../library/Hook/Autoload.php';

// Required unit test files.
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/Runner/Version.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';

// Set Error Reporting to E_ALL | E_STRICT.
error_reporting(E_ALL | E_STRICT);

// Determine the root directories of the tests of hook framework.

// Define as in Hook file in root directory.
define('HF_ROOT', dirname(__DIR__) . '/');
define('HF_TEST_DIR', __DIR__ . '/');

// For simple access on test files.
define('HF_TEST_FILES_DIR', HF_TEST_DIR . 'HookTest/_files/');

/*
    Prepend the Framework Core/ and tests/ directories to the include_path.
    This allows the tests to run out of the box and helps prevent loading other
    copies of the framework code and tests that would supersede this copy.
*/

$aPath = array(
    HF_ROOT,
    HF_TEST_DIR,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $aPath));


/**
 * Define Example SVN directory and subversion test binary.
 */

define('HF_TEST_SVN_BIN', __DIR__ . '/HookTest/_files/bin/');
define('HF_TEST_SVN_EXAMPLE', __DIR__ . '/HookTest/_files/ExampleSvn/');
define('HF_TEST_SVN_REPOSITORY', __DIR__ . '/../Repositories/');

/**
 * Define Example GIT directory and git test binary.
 */

define('HF_TEST_GIT_BIN', __DIR__ . '/HookTest/_files/bin/');
define('HF_TEST_GIT_EXAMPLE', __DIR__ . '/HookTest/_files/ExampleGit/');
define('HF_TEST_GIT_REPOSITORY', __DIR__ . '/../Repositories/');

/*
    Create Log instance and clear the file.
*/

$sFile = 'config.ini';
if (false === file_exists($sFile)) {
    $sFile = 'config-dist.ini';
}

$aCfg = array();
if (true === file_exists(HF_TEST_DIR . $sFile)) {
    $aCfg = parse_ini_file(HF_TEST_DIR . $sFile);
}

if ((true === empty($aCfg)) || (false === isset($aCfg['logfile']))) {
    $aCfg['logfile'] = __DIR__ . '/common-test.log';
    $aCfg['logmode'] = Log::HF_VARDUMP;
}

exec('echo > ' . $aCfg['logfile']);

if (false === isset($aCfg['logmode'])) {
    $aCfg['logmode'] = Log::HF_VARDUMP;
}

// Create a log object.
$oLog = Log::getInstance();
$oLog->setLogFile($aCfg['logfile']);
$oLog->setLogMode($aCfg['logmode']);

// Unset global variables that are no longer needed.
unset($sRoot);
