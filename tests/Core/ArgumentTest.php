<?php
/**
 * Argument Tests.
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

/**
 * Argument Tests.
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
class ArgumentTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Testobjekt.
	 * @var Arguments
	 */
	private $oArguments;

	/**
	 * Simuliertes SVN Verzeichnis.
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
	 * Dataprovider.
	 * @return array
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function getStartArguments()
	{
		return array(
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'user'  => 'testuser12',
				  'hook'  => 'start-commit',
				  'ok'    => true
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'user'  => 'testuser',
				  'hook'  => 'start-commit',
				  'ok'    => true
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'user'  => '',
				  'hook'  => 'start-commit',
				  'ok'    => false
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '',
				  'user'  => 'testuser',
				  'hook'  => 'start-commit',
				  'ok'    => false
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/x/x/svn',
				  'user'  => 'testuser',
				  'hook'  => 'start-commit',
				  'ok'    => false
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'user'  => 'testuser',
				  'hook'  => '',
				  'ok'    => false
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'user'  => 'testuser',
				  'hook'  => 'startcommit',
				  'ok'    => false
				 )
				),
				array(
				 array(
				  'path'  => '',
				  'repos' => '',
				  'user'  => '',
				  'hook'  => '',
				  'ok'    => false
				 )
				)
			   );
	} // function

	/**
	 * Testen ob die Argumente richtig geprueft werden.
	 * @param array $aData Testdatenset.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 * @dataProvider getStartArguments
	 */
	public function testStartArguments(array $aData)
	{
		if ($aData['repos'] === '')
		{
			$this->sSvn = '';
		} // if

		if ($aData['repos'] === '/x/x/svn')
		{
			$this->sSvn = '/x/x/svn';
		} // if

		$bExpect = $aData['ok'];
		$aData   = array(
					$aData['path'],
					$this->sSvn,
					$aData['user'],
					$aData['hook']
				   );

		$oArguments = new Arguments($aData);

		$this->assertEquals($bExpect, $oArguments->argumentsOk());
	} // function

	/**
	 * Dataprovider.
	 * @return array
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function getPreCommitArguments()
	{
		return array(
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'txn'   => '666-xx',
				  'hook'  => 'pre-commit',
				  'ok'    => true
				 ),
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'txn'   => '666-5j',
				  'hook'  => 'pre-commit',
				  'ok'    => true
				 ),
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'txn'   => '666-11',
				  'hook'  => 'pre-commit',
				  'ok'    => true
				 ),
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'txn'   => '666-x',
				  'hook'  => 'pre-commit',
				  'ok'    => true
				 ),
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'txn'   => '666-1',
				  'hook'  => 'pre-commit',
				  'ok'    => true
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'txn'   => '',
				  'hook'  => 'pre-commit',
				  'ok'    => false
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'txn'   => 'testuser',
				  'hook'  => 'pre-commit',
				  'ok'    => false
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/x/x/svn',
				  'txn'   => '66',
				  'hook'  => 'pre-commit',
				  'ok'    => false
				 )
				)
			   );
	} // function

	/**
	 * Testen der Pre Argumente beim Commit.
	 * @param array $aData Testdaten.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @dataProvider getPreCommitArguments
	 */
	public function testPreCommitArguments(array $aData)
	{
		if ($aData['repos'] === '/x/x/svn')
		{
			$this->sSvn = '/x/x/svn';
		} // if

		$bExpect = $aData['ok'];
		$aData   = array(
					$aData['path'],
					$this->sSvn,
					$aData['txn'],
					$aData['hook']
				   );

		$oArguments = new Arguments($aData);
		$this->assertEquals($bExpect, $oArguments->argumentsOk());
	} // function

	/**
	 * Dataprovider.
	 * @return array
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function getPostCommitArguments()
	{
		return array(
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'rev'   => '666',
				  'hook'  => 'post-commit',
				  'ok'    => true
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'rev'   => '',
				  'hook'  => 'post-commit',
				  'ok'    => false
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/var/local/svn',
				  'rev'   => 'testuser',
				  'hook'  => 'post-commit',
				  'ok'    => false
				 )
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/x/x/svn',
				  'rev'   => '666-1',
				  'hook'  => 'post-commit',
				  'ok'    => false
				 )
				)
			   );
	} // function

	/**
	 * Testen der Pre Argumente beim Commit.
	 * @param array $aData Testdaten.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @dataProvider getPostCommitArguments
	 */
	public function testPostCommitArguments(array $aData)
	{
		if ($aData['repos'] === '/x/x/svn')
		{
			$this->sSvn = '/x/x/svn';
		} // if

		$bExpect = $aData['ok'];
		$aData   = array(
					$aData['path'],
					$this->sSvn,
					$aData['rev'],
					$aData['hook']
				   );

		$oArguments = new Arguments($aData);
		$this->assertEquals($bExpect, $oArguments->argumentsOk());
	} // function

	/**
	 * Test zu wenige Argumente.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testToFewArguments()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => '666-1',
				  2 => 'pre-commit',
				 );

		$oArguments = new Arguments($aData);

		$this->assertFalse($oArguments->argumentsOk());
	} // function

	/**
	 * Test ob Werte richtig zurueck gegeben werden.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesStartCommit()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => 'testuser12',
				  3 => 'start-commit'
				 );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk());
		$this->assertEquals($this->sSvn, $oArguments->getRepository());
		$this->assertEquals('testuser12', $oArguments->getUser());
		$this->assertEquals('start-commit', $oArguments->getMainHook());
		$this->assertEquals('start', $oArguments->getMainType());
		$this->assertEquals('commit', $oArguments->getSubType());
	} // function

	/**
	 * Test ob Werte richtig zurueck gegeben werden.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesPreCommit()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => '666-1',
				  3 => 'pre-commit',
				 );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk());
		$this->assertEquals($this->sSvn, $oArguments->getRepository());
		$this->assertEquals('666-1', $oArguments->getTransaction());
		$this->assertEquals('pre-commit', $oArguments->getMainHook());
		$this->assertEquals('pre', $oArguments->getMainType());
		$this->assertEquals('pre', $oArguments->getMainType());
		$this->assertEquals('commit', $oArguments->getSubType());
	} // function

	/**
	 * Test ob Werte richtig zurueck gegeben werden.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesPostCommit()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => 666,
				  3 => 'post-commit'
				 );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk());
		$this->assertEquals($this->sSvn, $oArguments->getRepository());
		$this->assertEquals(666, $oArguments->getRevision());
		$this->assertEquals('post-commit', $oArguments->getMainHook());
		$this->assertEquals('post', $oArguments->getMainType());
		$this->assertEquals('commit', $oArguments->getSubType());
	} // function

	/**
	 * Test ob Werte richtig zurueck gegeben werden.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesPreLock()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => 'testuser',
				  3 => '/hookframework/trunk/Core/Hook.php',
				  4 => 'pre-lock'
				 );

		$oArguments = new Arguments($aData);

		$sHook = '/hookframework/trunk/Core/Hook.php';
		$this->assertTrue($oArguments->argumentsOk());
		$this->assertEquals($this->sSvn, $oArguments->getRepository());
		$this->assertEquals($sHook, $oArguments->getFile());
		$this->assertEquals('pre-lock', $oArguments->getMainHook());
		$this->assertEquals('pre', $oArguments->getMainType());
		$this->assertEquals('lock', $oArguments->getSubType());
	} // function

	/**
	 * Test ob Werte richtig zurueck gegeben werden.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesPreRevPropChange()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => 2009,
				  3 => 'testuser',
				  4 => 'ignore',
				  5 => 'A',
				  6 => 'pre-revprop-change',
				 );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk());
		$this->assertEquals($this->sSvn, $oArguments->getRepository());
		$this->assertEQuals(2009, $oArguments->getRevision());
		$this->assertEquals('pre-revprop-change', $oArguments->getMainHook());
		$this->assertEquals('pre', $oArguments->getMainType());
		$this->assertEquals('revprop-change', $oArguments->getSubType());
		$this->assertEquals('ignore', $oArguments->getPropertyName());
		$this->assertEquals('A', $oArguments->getAction());
	} // function

	/**
	 * Test ob Werte richtig zurueck gegeben werden. X ist nicht korrekt.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesPreRevPropChangeFalse()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => 2009,
				  3 => 'testuser',
				  4 => 'ignore',
				  5 => 'X',
				  6 => 'pre-revprop-change',
				 );

		$oArguments = new Arguments($aData);

		$sHook = '/hookframework/trunk/Core/Hook.php';
		$this->assertFalse($oArguments->argumentsOk());
	} // function

	/**
	 * Test ob Werte richtig zurueck gegeben werden.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testSubActions1()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => '666-1',
				  3 => 'pre-commit',
				 );

		$aExpect = array(
					'commit', 'lock', 'revprop-change', 'unlock'
				   );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk());
		$this->assertEquals($aExpect, $oArguments->getSubActions());
	} // function

	/**
	 * Test ob Werte richtig zurueck gegeben werden.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testSubActions2()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => 666,
				  3 => 'post-commit'
				 );

		$aExpect = array(
					'commit', 'lock', 'revprop-change', 'unlock'
				   );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk());
		$this->assertEquals($aExpect, $oArguments->getSubActions());
	} // function

	/**
	 * Test ob Werte richtig zurueck gegeben werden.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testSubActions3()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => $this->sSvn,
				  2 => 'testuser12',
				  3 => 'start-commit'
				 );

		$aExpect = array('commit');

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk());
		$this->assertEquals($aExpect, $oArguments->getSubActions());
	} // function
} // class
