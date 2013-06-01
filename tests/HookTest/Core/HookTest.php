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

require_once HF_TEST_DIR . 'Bootstrap.php';

/**
 * Hook Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class HookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Switch for ob start..
     * @var boolean
     */
    private $bObStart;

    /**
     * Setup.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUp()
    {
        $this->bObStart = false;
    }

    /**
     * Tear down.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function tearDown()
    {
        if (true === $this->bObStart) {
            ob_end_clean();
        }
    }

    /**
     * Test arguments error throwing exception.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testWrongArguments()
    {
        $this->setExpectedException('Exception', 'Arguments Error');

        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => HF_TEST_SVN_EXAMPLE,
            2 => 'phoebe',
            3 => 'pre-commit'
        );

        $oHook = new Hook($aData);
        $iExit = $oHook->run();

        $this->assertEquals(1, $iExit, 'Exit code false.');
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

        ob_start();
        $this->bObStart = true;
        $oHook          = new Hook($aData);
        $iExit          = $oHook->run();

        $this->assertEquals(1, $iExit);

        ob_end_clean();
        $this->bObStart = false;
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
}
