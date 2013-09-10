<?php
/**
 * Argument Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Adapter\Git;

use Hook\Adapter\Git\Arguments;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Argument Tests.
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
class ArgumentsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test totally wrong arguments.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testWrongArguments()
    {
        $aData = array(
            '/path/to/Hook', 'io', 'zilli-caramello'
        );

        $oArguments = new Arguments($aData);

        $this->assertFalse($oArguments->argumentsOk());
    }

    /**
     * Test with no arguments provided.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testNoArguments()
    {
        $oArguments = new Arguments(array());

        $this->assertFalse($oArguments->argumentsOk());
    }

    /**
     * Test too few arguments.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testToFewArguments()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => '4b825dc642cb6eb9a060e54bf8d69288fbee4904',
            2 => 'pre-commit',
        );

        $oArguments = new Arguments($aData);

        $this->assertFalse($oArguments->argumentsOk());
    }

    /**
     * Test if values are returned correctly.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testCorrectParameter()
    {
        $sTxn  = '4b825dc642cb6eb9a060e54bf8d69288fbee4904';
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => HF_TEST_GIT_EXAMPLE,
            2 => $sTxn,
            3 => 'client.pre-commit',
        );

        $aSub = array(
                 'pre-commit', 'prepare-commit-msg', 'commit-msg', 'post-commit',
                 'applypatch-msg', 'pre-applypath', 'post-applypatch', 'pre-rebase',
                 'post-checkout', 'post-merge'
                );

        $oArguments = new Arguments($aData);

        // Now check all data.
        $this->assertTrue($oArguments->argumentsOk(), $oArguments->getError());
        $this->assertSame('client.pre-commit', $oArguments->getMainHook(), 'Main hook false.');
        $this->assertSame('pre-commit', $oArguments->getSubType(), 'Sub type false.');
        $this->assertSame('client', $oArguments->getMainType(), 'Main type false.');
        $this->assertSame($aSub, $oArguments->getSubActions(), 'Sub actions false.');
        $this->assertSame(HF_TEST_GIT_EXAMPLE, $oArguments->getRepository(), 'Repository false.');
        $this->assertSame('ExampleGit', $oArguments->getRepositoryName(), 'Repository Name false.');
        $this->assertSame($sTxn, $oArguments->getTransaction(), 'Txn false.');
    }

    /**
     * Test if values are returned correctly.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSubActions1()
    {
        $aData = array(
                  0 => '/var/local/svn/hooks/Hook',
                  1 => HF_TEST_GIT_EXAMPLE,
                  2 => '4b825dc642cb6eb9a060e54bf8d69288fbee4904',
                  3 => 'client.pre-commit',
                 );

        $oArguments = new Arguments($aData);

        $this->assertTrue($oArguments->argumentsOk(), 'Arguments false');
    }

    /**
     * Test the values for a prepare commit message hook.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testPrepareCommitMessage()
    {
        $aData = array(
                  0 => '/path/to/Hook',
                  1 => HF_TEST_GIT_EXAMPLE,
                  2 => 'd3c57c9bce575082af8b7a0bb6d2f836a46cb4a5',
                  3 => '.git/COMMIT_EDITMSG',
                  4 => 'message',
                  5 => 'client.prepare-commit-msg'
                 );

        $oArguments = new Arguments($aData);

        $this->assertTrue($oArguments->argumentsOk(), 'Arguments false');
        $this->assertSame($aData[3], $oArguments->getCommitMessageFile(), 'Message File false');
        $this->assertSame($aData[4], $oArguments->getCommitMessageAction(), 'Action false');
    }
}
