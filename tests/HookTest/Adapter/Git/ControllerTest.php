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

namespace HookTest\Adapter\Git;

use Hook\Adapter\Git\Controller;
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
     * Test object git controller.
     * @var Controller
     */
    private $oFixture;

    /**
     * Prepare the config mock objects.
     * @return Hook\Core\Config;
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
     * @return Hook\Core\Log
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getMockLog()
    {
        $oLog = $this->getMock('Hook\Core\Log');

        return $oLog;
    }

    /**
     * Test controller for pre-commit hook.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testControllerPreCommit()
    {
        $aArguments = array(
                       '/path/to/Hook',
                       HF_TEST_GIT_EXAMPLE,
                       'd3c57c9bce575082af8b7a0bb6d2f836a46cb4a5',
                       'client.pre-commit'
                      );

        // Get the mock objects.
        $oConfig = $this->getMockConfig();
        $oLog    = $this->getMockLog();

        $this->oFixture = new Controller($aArguments);
        $this->oFixture->init($oConfig, $oLog, new Response());
        $oResponse = $this->oFixture->run();

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
        $aArguments = array(
                       '/path/to/Hook',
                       HF_TEST_GIT_EXAMPLE,
                       'fd43d0f959ffcddf8a36e1cc6adb43129ddd36a1',
                       'client.pre-commit'
                      );

        // Get the mock objects.
        $oConfig = $this->getMockConfig();
        $oLog    = $this->getMockLog();

        $this->oFixture = new Controller($aArguments);
        $this->oFixture->init($oConfig, $oLog, new Response());
        $oResponse = $this->oFixture->run();

        // Assert a syntax error and a phpcs style error text.
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
        $aArguments = array(
                       '/path/to/Hook',
                       HF_TEST_GIT_EXAMPLE,
                       'd3c57c9bce575082af8b7a0bb6d2f836a46cb4a5',
                       '.git/COMMIT_EDITMSG',
                       'message',
                       'client.prepare-commit-msg'
                      );

        // Get the mock objects.
        $oConfig = $this->getMockConfig();
        $oLog    = $this->getMockLog();

        $this->oFixture = new Controller($aArguments);
        $this->oFixture->init($oConfig, $oLog, new Response());
        $oResponse = $this->oFixture->run();

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
        $aArguments = array(
                       '/path/to/Hook',
                       HF_TEST_GIT_EXAMPLE,
                       'd3c57c9bce575082af8b7a0bb6d2f836a46cb4a5',
                       '.git/COMMIT_EMPTYMSG',
                       'client.commit-msg'
                      );

        // Get the mock objects.
        $oConfig = $this->getMockConfig();
        $oLog    = $this->getMockLog();

        $this->oFixture = new Controller($aArguments);
        $this->oFixture->init($oConfig, $oLog, new Response());
        $oResponse = $this->oFixture->run();

        // Assert a missing commit text.
        $sFile = __DIR__ . '/_files/Controller/expected-PreCommitMsgError-Response.txt';
        $sText = file_get_contents($sFile);

        $this->assertSame(1, $oResponse->getResult(), 'Result should be 1');
        $this->assertSame($sText, $oResponse->getText(), 'Error text is false');
    }

    /**
     * Test the usage function
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testWrongParameter()
    {
        // Get the mock objects.
        $oConfig = $this->getMockConfig();
        $oLog    = $this->getMockLog();

        $aArguments = array(
                       '/path/to/Hook',
                       HF_TEST_GIT_EXAMPLE,
                       'fd43d0f959ffcddf8a36e1cc6adb43129ddd36a1',
                       'client.pre-commit'
                      );

        $this->oFixture = new Controller($aArguments);
        $this->oFixture->init($oConfig, $oLog, new Response());

        $this->oFixture->showUsage();
    }

    /**
     * Test the usage function
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testInitArgumentFalseAndShowUsage()
    {
/*        $oArguments = $this->getMock('Hook\Adapter\Git\Arguments', array(), array(), '', false);

        $oArguments->expects($this->once())
                   ->method('getMainType')
                   ->will($this->returnValue('Client'));

        // This should be called once.
        $oArguments->expects($this->once())
                   ->method('getSubType')
                   ->will($this->returnValue('pre-commit'));*/

        // Get the mock objects.
        $oConfig = $this->getMockConfig();
        $oLog    = $this->getMockLog();

        $aArguments = array(
                       '/path/to/Hook',
                       HF_TEST_GIT_EXAMPLE,
                       'AZ43d0f959ffcddf8a36e1cc6adb43129ddd36a1',
                       'client.pre-commit'
                      );

        $sMessage = 'Arguments Error. Transaction "'. $aArguments[2] . '" is not a valid hash.';
        $this->setExpectedException('Exception', $sMessage);

        $this->oFixture = new Controller($aArguments);
        $this->oFixture->init($oConfig, $oLog, new Response());

        $s = $this->oFixture->showUsage();
        var_dump($s);
    }
}
