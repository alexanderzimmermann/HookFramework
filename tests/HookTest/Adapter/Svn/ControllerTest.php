<?php
/**
 * Usage-Object Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Git
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace HookTest\Adapter\Svn;

use Hook\Adapter\Svn\Controller;
use Hook\Core\Response;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Usage-Object Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Git
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepare the config mock objects.
     * @return \Hook\Core\Config;
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getMockConfig()
    {
        $oConfig = $this->getMock('Hook\Core\Config');

        // Create a map of arguments to return values.
        $aMap = array(
                 array('path', 'repositories', HF_TEST_SVN_REPOSITORY),
                 array('log', 'logmode', 3),
                 array('vcs', 'binary_path', HF_TEST_SVN_BIN)
                );

        $oConfig->expects($this->any())
                ->method('getConfiguration')
                ->will($this->returnValueMap($aMap));

        return $oConfig;
    }

    /**
     * Prepare the log mock object.
     * @return \Hook\Core\Log
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getMockLog()
    {
        $oLog = $this->getMock('Hook\Core\Log');

        return $oLog;
    }

    /**
     * Prepare mock object arguments with some base return values.
     * @param boolean $bArgumentsOk Should this value true or false.
     * @param string  $sTxn         A transaction number.
     * @param string  $sHook        The complete hook (Main and sub).
     * @return \PHPUnit_Framework_MockObject_MockObject Hook\Adapter\Git\Arguments
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getMockArguments($bArgumentsOk, $sTxn, $sHook)
    {
        $aCommit = explode('-', $sHook);

        $oArguments = $this->getMock('Hook\Adapter\Svn\Arguments', array(), array(), '', false);

        $oArguments->expects($this->once())
                   ->method('argumentsOk')
                   ->will($this->returnValue($bArgumentsOk));

        $oArguments->expects($this->any())
                   ->method('getRepository')
                   ->will($this->returnValue(HF_TEST_SVN_EXAMPLE));

        $oArguments->expects($this->any())
                   ->method('getRepositoryName')
                   ->will($this->returnValue('ExampleSvn'));

        $oArguments->expects($this->any())
                   ->method('getTransaction')
                   ->will($this->returnValue($sTxn));

        $oArguments->expects($this->any())
                   ->method('getMainHook')
                   ->will($this->returnValue($sHook));

        $oArguments->expects($this->any())
                   ->method('getMainType')
                   ->will($this->returnValue($aCommit[0]));

        $oArguments->expects($this->any())
                   ->method('getSubType')
                   ->will($this->returnValue($aCommit[1]));

        $oArguments->expects($this->any())
                   ->method('getSubActions')
                   ->will($this->returnValue(array('commit', 'lock')));

        return $oArguments;
    }

    /**
     * Test that the git controller is returned.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetSvnControllerObject()
    {
        $aArguments = array(
            '/path/to/Hook',
            'abc',
            'pre-commit'
        );

        $oController = \Hook\Adapter\Svn\Controller::factory($aArguments);
        $this->assertInstanceOf('Hook\Adapter\Svn\Controller', $oController);
    }

    /**
     * Test pre hook that works fine.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testControllerPreCommitOk()
    {
        // Get the mock objects.
        $oConfig    = $this->getMockConfig();
        $oLog       = $this->getMockLog();
        $oArguments = $this->getMockArguments(true, '74-1', 'pre-commit');

        $oFixture = new Controller($oArguments);
        $oFixture->init($oConfig, $oLog, new Response());
        $oResponse = $oFixture->run();

        // Assert
        $this->assertSame(0, $oResponse->getResult(), 'Result should be 0');
        $this->assertSame('', $oResponse->getText(), 'Error text should be empty');
    }

    /**
     * Test pre hook with errors.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHookPreCommitWithError()
    {
        // Get the mock objects.
        $oConfig    = $this->getMockConfig();
        $oLog       = $this->getMockLog();
        $oArguments = $this->getMockArguments(true, '666-1', 'pre-commit');

        $oFixture = new Controller($oArguments);
        $oFixture->init($oConfig, $oLog, new Response());
        $oResponse = $oFixture->run();

        // Assert a phpcs style error text.
        $sFile = __DIR__ . '/_files/Controller/expected-PreCommitError-Response.txt';
        $sText = file_get_contents($sFile);

        $this->assertSame(1, $oResponse->getResult(), 'Result should be 1');
        $this->assertSame($sText, $oResponse->getText(), 'Error text is false');
    }

    /**
     * Test no listener were found, cause this is no bug nor a reason to deny a hook.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testControllerNoListenerFound()
    {
        // Get the mock objects.
        $oLog    = $this->getMockLog();
        $oConfig = $this->getMock('Hook\Core\Config');

        // Create a map of arguments to return values.
        $aMap = array(
                 array('path', 'repositories', HF_TEST_FILES_DIR . 'Repositories/'),
                 array('log', 'logmode', 3),
                 array('vcs', 'binary_path', HF_TEST_SVN_BIN)
                );

        $oConfig->expects($this->any())
                ->method('getConfiguration')
                ->will($this->returnValueMap($aMap));

        $oArguments = $this->getMock('Hook\Adapter\Svn\Arguments', array(), array(), '', false);

        $oArguments->expects($this->once())
                   ->method('argumentsOk')
                   ->will($this->returnValue(true));

        $oArguments->expects($this->any())
                   ->method('getRepository')
                   ->will($this->returnValue(HF_TEST_FILES_DIR . 'Repositories/'));

        $oArguments->expects($this->any())
                   ->method('getRepositoryName')
                   ->will($this->returnValue('Empty'));

        $oArguments->expects($this->any())
                   ->method('getTransaction')
                   ->will($this->returnValue('74-1'));

        $oArguments->expects($this->any())
                   ->method('getMainHook')
                   ->will($this->returnValue('pre-commit'));

        $oArguments->expects($this->any())
                   ->method('getMainType')
                   ->will($this->returnValue('pre'));

        $oArguments->expects($this->any())
                   ->method('getSubType')
                   ->will($this->returnValue('commit'));

        $oArguments->expects($this->any())
                   ->method('getSubActions')
                   ->will($this->returnValue(array('commit', 'lock')));

        $oResponse = new Response();

        $oFixture = new Controller($oArguments);
        $oFixture->init($oConfig, $oLog, $oResponse);

        $sExpected = 'no response text given or exit is ok.';
        $this->assertSame(0, $oResponse->getResult(), 'Result should be 0');
        $this->assertSame($sExpected, $oResponse->getText(), 'Result text is false');
    }

    /**
     * Found files in the directory according to the hook, but with incorrect implementation
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testFilesFoundButBadImplementationSoNoListener()
    {
        // Get the mock objects.
        $oLog    = $this->getMockLog();
        $oConfig = $this->getMock('Hook\Core\Config');

        // Create a map of arguments to return values.
        $aMap = array(
            array('path', 'repositories', HF_TEST_FILES_DIR . 'Repositories/'),
            array('log', 'logmode', 3),
            array('vcs', 'binary_path', HF_TEST_SVN_BIN)
        );

        $oConfig->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValueMap($aMap));

        $oArguments = $this->getMock('Hook\Adapter\Svn\Arguments', array(), array(), '', false);

        $oArguments->expects($this->once())
            ->method('argumentsOk')
            ->will($this->returnValue(true));

        $oArguments->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue(HF_TEST_FILES_DIR . 'Repositories/'));

        $oArguments->expects($this->any())
            ->method('getRepositoryName')
            ->will($this->returnValue('Failures'));

        $oArguments->expects($this->any())
            ->method('getTransaction')
            ->will($this->returnValue('74-1'));

        $oArguments->expects($this->any())
            ->method('getMainHook')
            ->will($this->returnValue('pre-commit'));

        $oArguments->expects($this->any())
            ->method('getMainType')
            ->will($this->returnValue('pre'));

        $oArguments->expects($this->any())
            ->method('getSubType')
            ->will($this->returnValue('commit'));

        $oArguments->expects($this->any())
            ->method('getSubActions')
            ->will($this->returnValue(array('commit', 'lock')));

        $oResponse = new Response();

        $oFixture = new Controller($oArguments);
        $oFixture->init($oConfig, $oLog, $oResponse);

        $sExpected = 'no response text given or exit is ok.';
        $this->assertSame(0, $oResponse->getResult(), 'Result should be 0');
        $this->assertSame($sExpected, $oResponse->getText(), 'Result text is false');
    }
}
