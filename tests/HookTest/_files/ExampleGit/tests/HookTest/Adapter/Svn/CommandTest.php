<?php
/**
 * Command Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace HookTest\Adapter\Svn;

use Hook\Adapter\Svn\Command;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Command Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn
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
        $aFunctions = array(
            'getRepository', 'getMainType', 'getTransaction', 'getRevision'
        );

        $aArguments = array(
            array(
                0 => '/var/local/svn/hooks/Hook',
                1 => HF_TEST_SVN_EXAMPLE,
                2 => 'Zora',
                3 => 'pre-commit'
            )
        );

        // Main type usually is pre, post and start but here Failures to check listener.
        $oArguments = $this->getMock('Hook\Adapter\Svn\Arguments', $aFunctions, $aArguments);

        $oArguments->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue('ExampleSvn'));

        $oArguments->expects($this->exactly(2))
            ->method('getMainType')
            ->will($this->returnValue('pre'));


        $oArguments->expects($this->once())
            ->method('getTransaction')
            ->will($this->returnValue('10-1'));


        $oArguments->expects($this->never())
            ->method('getRevision')
            ->will($this->returnValue('10'));

        $this->oCommand->init($oArguments);

        $aExpected = array(0 => 'svnlook: E160007: No such transaction 10-1');

        $this->assertSame($aExpected, $this->oCommand->getInfo());
    }
}
