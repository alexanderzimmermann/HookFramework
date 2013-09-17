<?php
/**
 * Command Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Git
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace HookTest\Adapter\Git;

use Hook\Adapter\Git\Command;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Command Tests.
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
class CommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Command object.
     * @var Command
     */
    protected $oCommand;

    /**
     * Setup.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setUp()
    {
        $sCommand = HF_TEST_FILES_DIR . 'bin/';
        $this->oCommand = new Command($sCommand);
    }

    /**
     * Test no valid revision or transaction
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testNoValidTransactionOrRevision()
    {
        // Main type usually is pre, post and start but here Failures to check listener.
        $oArguments = $this->getMock('Hook\Adapter\Git\Arguments', array(), array(), '', false);

        $oArguments->expects($this->once())
                   ->method('getRepository')
                   ->will($this->returnValue('ExampleGit'));

        // This should be called once.
        $oArguments->expects($this->once())
                   ->method('getTransaction')
                   ->will($this->returnValue('234523454'));

        $this->oCommand->init($oArguments);

        $aExpected = array(
                      0 => 'fatal: ambiguous argument \'234523454\': unknown revision or path not '
                           . 'in the working tree.',
                      1 => 'Use \'--\' to separate paths from revisions'
                     );

        /*
            alexander@alexander:~/Projekte/HookFramework/tests/HookTest/Adapter/Git/Parser$ git diff 89aa8234
            fatal: ambiguous argument '89aa8234': unknown revision or path not in the working tree.
            Use '--' to separate paths from revisions
        */

        $this->assertSame($aExpected, $this->oCommand->getCommitDiff());
    }

    /**
     * Test call of function getInfo
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetInfo()
    {
        // Main type usually is pre, post and start but here Failures to check listener.
        $oArguments = $this->getMock('Hook\Adapter\Git\Arguments', array(), array(), '', false);

        $oArguments->expects($this->once())
                   ->method('getRepository')
                   ->will($this->returnValue('ExampleGit'));

        // This should be called once.
        $oArguments->expects($this->once())
                   ->method('getTransaction')
                   ->will($this->returnValue('234523454'));

        $this->oCommand->init($oArguments);

        $aExpected = array(
                      0 => 'alexanderzimmermann <alex@azimmermann.com> 1377963964 +0200'
                     );

        $this->assertSame($aExpected, $this->oCommand->getInfo());
    }

    /**
     * Test when the command returns an error cause of wrong parameters.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testBadCommandCall()
    {        // Main type usually is pre, post and start but here Failures to check listener.
        $oArguments = $this->getMock('Hook\Adapter\Git\Arguments', array(), array(), '', false);

        $oArguments->expects($this->once())
                   ->method('getRepository')
                   ->will($this->returnValue('ExampleGit'));

        // This should be called once.
        $oArguments->expects($this->once())
                   ->method('getTransaction')
                   ->will($this->returnValue('234523454'));

        $oCommand = new Command(HF_TEST_FILES_DIR . 'bin/error-');
        $oCommand->init($oArguments);
        $oCommand->getUser();

        $this->assertTrue($oCommand->hasError());
    }

    /**
     * Test when the command returns nothing, also when the command does not exists.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testEmptyCommandResult()
    {        // Main type usually is pre, post and start but here Failures to check listener.
        $oArguments = $this->getMock('Hook\Adapter\Git\Arguments', array(), array(), '', false);

        $oArguments->expects($this->once())
                   ->method('getRepository')
                   ->will($this->returnValue('ExampleGit'));

        // This should be called once.
        $oArguments->expects($this->once())
                   ->method('getTransaction')
                   ->will($this->returnValue('234523454'));

        $oCommand = new Command(HF_TEST_FILES_DIR . 'bin/empty-');
        $oCommand->init($oArguments);
        $oCommand->getUser();

        $this->assertTrue($oCommand->hasError());
    }
}
