<?php
/**
 * Git Controller-Object Tests.
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

namespace HookTest\Adapter\Git;

use Hook\Adapter\Git\Controller;
use Hook\Core\Response;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Git Controller-Object Tests.
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
                 array('path', 'repositories', HF_TEST_GIT_REPOSITORY),
                 array('log', 'logmode', 3),
                 array('vcs', 'binary_path', HF_TEST_GIT_BIN)
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
        $aCommit = explode('.', $sHook);

        $oArguments = $this->getMock('Hook\Adapter\Git\Arguments', array(), array(), '', false);

        $oArguments->expects($this->once())
                   ->method('argumentsOk')
                   ->will($this->returnValue($bArgumentsOk));

        $oArguments->expects($this->any())
                   ->method('getRepository')
                   ->will($this->returnValue(HF_TEST_GIT_EXAMPLE));

        $oArguments->expects($this->any())
                   ->method('getRepositoryName')
                   ->will($this->returnValue('ExampleGit'));

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
                   ->will($this->returnValue(array('pre-commit', 'prepare-commit-msg', 'commit-msg')));

        return $oArguments;
    }

    /**
     * Test that the git controller is returned.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetGitControllerObject()
    {
        $aArguments = array(
                       '/path/to/Hook',
                       'abc',
                       'client.pre-commit'
                      );

        $oController = Controller::factory($aArguments);
        $this->assertInstanceOf('Hook\Adapter\Git\Controller', $oController);
    }

    /**
     * Test controller for pre-commit hook.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testControllerPreCommit()
    {
        // Get the mock objects.
        $oConfig    = $this->getMockConfig();
        $oLog       = $this->getMockLog();
        $oArguments = $this->getMockArguments(true, 'd3c57c9bce575082af8b7a0bb6d2f836a46cb4a5', 'client.pre-commit');

        $oFixture = new Controller($oArguments);
        $oFixture->init($oConfig, $oLog, new Response());
        $oResponse = $oFixture->run();

        // Assert
        $this->assertSame(0, $oResponse->getResult(), 'Result should be 0');
        $this->assertSame('', $oResponse->getText(), 'Error text should be empty');
    }

    /**
     * Test controller for pre-commit hook.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testControllerPreCommitError()
    {
        // Get the mock objects.
        $oConfig    = $this->getMockConfig();
        $oLog       = $this->getMockLog();
        $oArguments = $this->getMockArguments(true, 'fd43d0f959ffcddf8a36e1cc6adb43129ddd36a1', 'client.pre-commit');

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
     * TEst controller for prepare-commit-msg hook.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testControllerPrepareCommitMsg()
    {
        // Get the mock objects.
        $sTxn = 'd3c57c9bce575082af8b7a0bb6d2f836a46cb4a5';
        $oArguments = $this->getMockArguments(true, $sTxn, 'client.prepare-commit-msg');

        $oArguments->expects($this->once())
                   ->method('getCommitMessageFile')
                   ->will($this->returnValue('.git/COMMIT_EDITMSG'));

        $oConfig = $this->getMockConfig();
        $oLog    = $this->getMockLog();

        $oFixture = new Controller($oArguments);
        $oFixture->init($oConfig, $oLog, new Response());
        $oResponse = $oFixture->run();

        // Assert
        $this->assertSame(0, $oResponse->getResult(), 'Result should be 0');
        $this->assertSame('', $oResponse->getText(), 'Error text is false');
    }

    /**
     * TEst controller for prepare-commit-msg hook.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testControllerCommitMsgError()
    {
        // Get the mock objects.
        $oArguments = $this->getMockArguments(true, 'd3c57c9bce575082af8b7a0bb6d2f836a46cb4a5', 'client.commit-msg');

        $oArguments->expects($this->once())
            ->method('getCommitMessageFile')
            ->will($this->returnValue('.git/COMMIT_EMPTYMSG'));

        $oConfig = $this->getMockConfig();
        $oLog    = $this->getMockLog();

        $oFixture = new Controller($oArguments);
        $oFixture->init($oConfig, $oLog, new Response());
        $oResponse = $oFixture->run();

        // Assert a missing commit text.
        $sFile = __DIR__ . '/_files/Controller/expected-PreCommitMsgError-Response.txt';
        $sText = file_get_contents($sFile);

        $this->assertSame(1, $oResponse->getResult(), 'Result should be 1');
        $this->assertSame($sText, $oResponse->getText(), 'Error text is false');
    }

    /**
     * Test that the exception will be thrown.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testWrongParameter()
    {
        // Get the mock objects.
        $sTxn       = 'AZ43d0f959ffcddf8a36e1cc6adb43129ddd36a1';
        $sMessage   = 'Arguments Error. Transaction "'. $sTxn . '" is not a valid hash.';
        $oConfig    = $this->getMockConfig();
        $oLog       = $this->getMockLog();
        $oArguments = $this->getMockArguments(false, $sTxn, 'client.pre-commit');
        $oArguments->expects($this->once())
                   ->method('argumentsOk')
                   ->will($this->returnValue(false));

        $oArguments->expects($this->once())
                   ->method('getError')
                   ->will($this->returnValue($sMessage));

        $this->setExpectedException('Exception', $sMessage);

        $oFixture = new Controller($oArguments);
        $oFixture->init($oConfig, $oLog, new Response());
    }

    /**
     * Test the usage function
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testInitArgumentsFalseAndShowUsage()
    {
        // Get the mock objects.
        $oConfig    = $this->getMockConfig();
        $oLog       = $this->getMockLog();
        $oArguments = $this->getMockArguments(false, 'fd43d0f959ffcddf8a36e1cc6adb43129ddd36a1', 'client.pre-commit');
        $oArguments->expects($this->once())
                   ->method('getError')
                   ->will($this->returnValue('Arguments false'));

        try {
            $oFixture = new Controller($oArguments);
            $oFixture->init($oConfig, $oLog, new Response());
        } catch (\Exception $oE) {
        }

        $sFile = __DIR__ . '/_files/Controller/expected-InitArgumentsFalseAndShowUsage.txt';
        $sText = file_get_contents($sFile);

        $this->assertSame($sText, $oFixture->showUsage());
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
                 array('vcs', 'binary_path', HF_TEST_GIT_BIN)
                );

        $oConfig->expects($this->any())
                ->method('getConfiguration')
                ->will($this->returnValueMap($aMap));

        $oArguments = $this->getMock('Hook\Adapter\Git\Arguments', array(), array(), '', false);

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
                   ->will($this->returnValue('fd43d0f959ffcddf8a36e1cc6adb43129ddd36a1'));

        $oArguments->expects($this->any())
                   ->method('getMainHook')
                   ->will($this->returnValue('client.pre-commit'));

        $oArguments->expects($this->any())
                   ->method('getMainType')
                   ->will($this->returnValue('client'));

        $oArguments->expects($this->any())
                   ->method('getSubType')
                   ->will($this->returnValue('pre-commit'));

        $oArguments->expects($this->any())
                   ->method('getSubActions')
                   ->will($this->returnValue(array('pre-commit', 'prepare-commit-msg', 'commit-msg')));

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
                 array('vcs', 'binary_path', HF_TEST_GIT_BIN)
                );

        $oConfig->expects($this->any())
                ->method('getConfiguration')
                ->will($this->returnValueMap($aMap));

        $oArguments = $this->getMock('Hook\Adapter\Git\Arguments', array(), array(), '', false);

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
                   ->will($this->returnValue('fd43d0f959ffcddf8a36e1cc6adb43129ddd36a1'));

        $oArguments->expects($this->any())
                   ->method('getMainHook')
                   ->will($this->returnValue('client.pre-commit'));

        $oArguments->expects($this->any())
                   ->method('getMainType')
                   ->will($this->returnValue('client'));

        $oArguments->expects($this->any())
                   ->method('getSubType')
                   ->will($this->returnValue('pre-commit'));

        $oArguments->expects($this->any())
                   ->method('getSubActions')
                   ->will($this->returnValue(array('pre-commit', 'prepare-commit-msg', 'commit-msg')));

        $oResponse = new Response();

        $oFixture = new Controller($oArguments);
        $oFixture->init($oConfig, $oLog, $oResponse);

        $sExpected = 'no response text given or exit is ok.';
        $this->assertSame(0, $oResponse->getResult(), 'Result should be 0');
        $this->assertSame($sExpected, $oResponse->getText(), 'Result text is false');
    }
}
