<?php
/**
 * Hook Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once dirname(__FILE__) . '/../TestHelper.php';

require_once 'Core/Arguments.php';
require_once 'Core/Listener/ListenerParser.php';
require_once 'tests/Core/HookHelper.php';

/**
 * Hook Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class HookTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Fake SVN Pfad.
	 * @var string
	 */
	private $sSvn;

	/**
	 * SetUp Operationen.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->sSvn = dirname(__FILE__) . '/svn';
	} // function

	/**
	 * Testen Ausgabe Usage.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testHookUsage()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => 'testuser12',
				  3 => 'pre-commit'
				 );

		$oHook = new HookHelperMain($aData);

		// Ausgabe unterdruecken.
		ob_start();
		$iExit = $oHook->run();

		$sContent = ob_get_contents();
		ob_end_clean();

		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$TXN      Transaction (74-1)' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n";
		$sExpected .= 'Beispiel: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $TXN pre-commit' . "\n";

		$this->assertEquals(1, $iExit);
		$this->assertEquals($sExpected, $sContent);
	} // function

	/**
	 * Testen des Start Hooks.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testHookStart()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => 'testuser12',
				  3 => 'start-commit'
				 );

		$oHook = new HookHelperMain($aData);
		$iExit = $oHook->run();

		$this->assertEquals(0, $iExit);
	} // function

	/**
	 * Testen des Pre Hooks.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testHookPreCommitWithError()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => '666-1',
				  3 => 'pre-commit',
				 );

		ob_start();
		$oHook = new HookHelperMain($aData);
		$iExit = $oHook->run();

		$this->assertEquals(1, $iExit);

		$sLines = ob_get_contents();
		ob_end_clean();
	} // function

	/**
	 * Testen des Pre Hooks.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testHookPreCommitOk()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => '74-1',
				  3 => 'pre-commit',
				 );

		$oHook = new HookHelperMain($aData);
		$iExit = $oHook->run();

		$this->assertEquals(0, $iExit);

		$sLines = ob_get_contents();
	} // function

	/**
	 * Testen des Post Hooks.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testHookPost()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => 76,
				  3 => 'post-commit',
				 );

		$oHook = new HookHelperMain($aData);
		$iExit = $oHook->run();

		// Post kann immer 0 sein, da hier nichts mehr abgebrochen werden kann.
		$this->assertEquals(0, $iExit);
	} // function
} // class
