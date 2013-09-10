<?php
/**
 * Hook Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Core;

use Hook\Core\Hook;
use Hook\Core\Response;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Hook Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class HookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test arguments error throwing exception.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testWrongArguments()
    {
        $aData = array(
                  0 => '/var/local/svn/hooks/Hook',
                  1 => HF_TEST_SVN_EXAMPLE,
                  2 => 'phoebe',
                  3 => 'pre-commit'
                 );

        $oResponse = new Response(fopen('/dev/null', 'w'));
        $oHook     = new Hook($aData);
        $oHook->setResponse($oResponse);
        $iExit = $oHook->run();

        $sExpected = 'Arguments Error. Transaction missing or wrong format.';

        $this->assertEquals(1, $iExit, 'Exit code false.');
        $this->assertSame($sExpected, $oResponse->getText(), 'Text is false.');
    }

    /**
     * Test start hook.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHookStart()
    {
        $aData = array(
                  0 => '/var/local/svn/hooks/Hook',
                  1 => HF_TEST_SVN_EXAMPLE,
                  2 => 'alice',
                  3 => 'start-commit'
                 );

        $oHook = new Hook($aData);
        $iExit = $oHook->run();

        $this->assertEquals(0, $iExit);
    }

    /**
     * Test pre hook with errors.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHookPreCommitWithError()
    {
        $aData = array(
                  0 => '/var/local/svn/hooks/Hook',
                  1 => HF_TEST_SVN_EXAMPLE,
                  2 => '666-1',
                  3 => 'pre-commit',
                 );

        $oResponse = new Response(fopen('/dev/null', 'w'));
        $oHook = new Hook($aData);
        $oHook->setResponse($oResponse);
        $iExit = $oHook->run();

        $sFile     = __DIR__ . '/_files/Hook/expected-' . __FUNCTION__ . '.txt';
        $sExpected = file_get_contents($sFile);

        $this->assertEquals(1, $iExit, 'Exit value is not 1.');
        $this->assertSame($sExpected, $oResponse->getText(), 'Result text false.');
    }

    /**
     * Test pre hook that works fine.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHookPreCommitOk()
    {
        $aData = array(
                  0 => '/var/local/svn/hooks/Hook',
                  1 => HF_TEST_SVN_EXAMPLE,
                  2 => '74-1',
                  3 => 'pre-commit',
                 );

        $oHook = new Hook($aData);
        $iExit = $oHook->run();

        $this->assertEquals(0, $iExit);
    }

    /**
     * Test post hook.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHookPost()
    {
        $aData = array(
                  0 => '/var/local/svn/hooks/Hook',
                  1 => HF_TEST_SVN_EXAMPLE,
                  2 => 76,
                  3 => 'post-commit',
                 );

        $oHook = new Hook($aData);
        $iExit = $oHook->run();

        // Post is always 0, cause an abort does not make sense here.
        $this->assertEquals(0, $iExit);
    }

    /**
     * Test a non existing revision.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testNonExistingRevision()
    {
        $aData = array(
                  0 => '/var/local/svn/hooks/Hook',
                  1 => HF_TEST_SVN_EXAMPLE,
                  2 => 10,
                  3 => 'post-commit',
                 );

        $oResponse = new Response(fopen('/dev/null', 'w'));
        $oHook     = new Hook($aData);
        $oHook->setResponse($oResponse);
        $iExit = $oHook->run();

        $sExpected = 'svnlook: E160006: No Revision 10';

        $this->assertEquals(1, $iExit, 'Exit value is not 1.');
        $this->assertSame($sExpected, $oResponse->getText(), 'Result text false.');
    }
}
