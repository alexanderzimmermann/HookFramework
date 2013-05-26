<?php
/**
 * Hook Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Core;

require_once __DIR__ . '/../../Bootstrap.php';

require_once __DIR__ . '/HookHelper.php';

/**
 * Hook Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
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
    } // function

    /**
     * Tear down.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function tearDown()
    {
        if (true === $this->bObStart) {
            ob_end_clean();
        } // if
    } // function

    /**
     * Test usage output.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHookUsage()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => TEST_SVN_EXAMPLE,
            2 => 'phoebe',
            3 => 'pre-commit'
        );

        $oHook = new HookHelper($aData);

        // Avoid output.
        ob_start();
        $this->bObStart = true;
        $iExit          = $oHook->run();

        $sContent = ob_get_contents();
        ob_end_clean();
        $this->bObStart = false;

        $sExpected = 'Call with the following parameters and order:' . "\n\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$TXN      Transaction (74-1)' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $TXN pre-commit' . "\n";

        $this->assertEquals(1, $iExit, 'Exit code false.');
        $this->assertEquals($sExpected, $sContent, 'Message not as expected.');
    } // function

    /**
     * Test start hook.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHookStart()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => TEST_SVN_EXAMPLE,
            2 => 'testuser12',
            3 => 'start-commit'
        );

        $oHook = new HookHelper($aData);
        $iExit = $oHook->run();

        $this->assertEquals(0, $iExit);
    } // function

    /**
     * Test pre hook with errors.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHookPreCommitWithError()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => TEST_SVN_EXAMPLE,
            2 => '666-1',
            3 => 'pre-commit',
        );

        ob_start();
        $this->bObStart = true;
        $oHook          = new HookHelper($aData);
        $iExit          = $oHook->run();

        $this->assertEquals(1, $iExit);

        ob_end_clean();
        $this->bObStart = false;
    } // function

    /**
     * Test pre hook that works fine.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHookPreCommitOk()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => TEST_SVN_EXAMPLE,
            2 => '74-1',
            3 => 'pre-commit',
        );

        $oHook = new HookHelper($aData);
        $iExit = $oHook->run();

        $this->assertEquals(0, $iExit);
    } // function

    /**
     * Test post hook.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHookPost()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => TEST_SVN_EXAMPLE,
            2 => 76,
            3 => 'post-commit',
        );

        $oHook = new HookHelper($aData);
        $iExit = $oHook->run();

        // Post is always 0, cause we do not abort here.
        $this->assertEquals(0, $iExit);
    } // function
} // class
