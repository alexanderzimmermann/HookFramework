<?php
/**
 * Argument Tests.
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

namespace CoreTest;

use Core\Arguments;

require_once __DIR__ . '/../Bootstrap.php';

require_once 'Core/Arguments.php';

/**
 * Argument Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class ArgumentTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test object.
	 * @var Arguments
	 */
	private $oArguments;

	/**
	 * Data provider.
	 * @return array
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function getStartArguments()
	{
		return array(
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'user'  => 'testuser12',
				  'hook'  => 'start-commit',
				 ), true
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'user'  => 'testuser',
				  'hook'  => 'start-commit',
				 ), true
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'user'  => '',
				  'hook'  => 'start-commit',
				 ), false
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '',
				  'user'  => 'testuser',
				  'hook'  => 'start-commit',
				 ), false
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/x/x/svn',
				  'user'  => 'testuser',
				  'hook'  => 'start-commit',
				 ), false
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'user'  => 'testuser',
				  'hook'  => '',
				 ), false
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'user'  => 'testuser',
				  'hook'  => 'startcommit',
				 ), false
				),
				array(
				 array(
				  'path'  => '',
				  'repos' => '',
				  'user'  => '',
				  'hook'  => '',
				 ), false
				)
			   );
	} // function

	/**
	 * Test for arguments check.
	 * @param array   $aData     Test data.
     * @param boolean $bExpected Expected Assert.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 * @dataProvider getStartArguments
	 */
	public function testStartArguments(array $aData, $bExpected)
	{
		$aData = array(
				  $aData['path'],
				  $aData['repos'],
				  $aData['user'],
				  $aData['hook']
				 );

		$oArguments = new Arguments($aData);

		$this->assertEquals($bExpected, $oArguments->argumentsOk(), $oArguments->getError());
	} // function

	/**
	 * Data provider.
	 * @return array
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function getPreCommitArguments()
	{
		return array(
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'txn'   => '666-xx',
				  'hook'  => 'pre-commit',
				 ), true
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'txn'   => '666-5j',
				  'hook'  => 'pre-commit',
				 ), true
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'txn'   => '666-11',
				  'hook'  => 'pre-commit',
				 ), true
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'txn'   => '666-x',
				  'hook'  => 'pre-commit',
				 ), true
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'txn'   => '666-1',
				  'hook'  => 'pre-commit',
				 ), true
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'txn'   => '',
				  'hook'  => 'pre-commit',
				 ), false
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'txn'   => 'testuser',
				  'hook'  => 'pre-commit',
				 ), false
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/x/x/svn',
				  'txn'   => '66',
				  'hook'  => 'pre-commit',
				 ), false
				)
			   );
	} // function

	/**
	 * Test of pre arguments on commit.
	 * @param array   $aData     Test data.
	 * @param boolean $bExpected Expected Value.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @dataProvider getPreCommitArguments
	 */
	public function testPreCommitArguments(array $aData, $bExpected)
	{
		$aData = array(
				  $aData['path'],
				  $aData['repos'],
				  $aData['txn'],
				  $aData['hook']
				 );

		$oArguments = new Arguments($aData);
		$this->assertEquals($bExpected, $oArguments->argumentsOk(), $oArguments->getError());
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
				  'repos' => TEST_SVN_EXAMPLE,
				  'rev'   => '666',
				  'hook'  => 'post-commit',
				 ), true
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'rev'   => '',
				  'hook'  => 'post-commit',
				 ), false
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => TEST_SVN_EXAMPLE,
				  'rev'   => 'testuser',
				  'hook'  => 'post-commit',
				 ), false
				),
				array(
				 array(
				  'path'  => '/var/local/svn/hooks/Hook',
				  'repos' => '/x/x/svn',
				  'rev'   => '666-1',
				  'hook'  => 'post-commit',
				 ), false
				)
			   );
	} // function

	/**
	 * Test arguments in pre commit.
	 * @param array   $aData     Test data.
	 * @param boolean $bExpected Expected assert.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @dataProvider getPostCommitArguments
	 */
	public function testPostCommitArguments(array $aData, $bExpected)
	{
		$aData = array(
				  $aData['path'],
				  $aData['repos'],
				  $aData['rev'],
				  $aData['hook']
				 );

		$oArguments = new Arguments($aData);
		$this->assertEquals($bExpected, $oArguments->argumentsOk(), $oArguments->getError());
	} // function

	/**
	 * Test too few arguments.
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
	 * Test with no arguments provided.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testNoArguemnts()
	{
		$oArguemnts = new Arguments(array());

		$this->assertFalse($oArguemnts->argumentsOk());
	} // function

	/**
	 * Test if values are returned correctly.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesStartCommit()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => TEST_SVN_EXAMPLE,
				  2 => 'testuser12',
				  3 => 'start-commit'
				 );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
		$this->assertEquals(TEST_SVN_EXAMPLE, $oArguments->getRepository(), 'Repository false');
		$this->assertEquals('Example', $oArguments->getRepositoryName(), 'ReposName false');
		$this->assertEquals('testuser12', $oArguments->getUser(), 'User false');
		$this->assertEquals('start-commit', $oArguments->getMainHook(), 'MainHook false');
		$this->assertEquals('start', $oArguments->getMainType(), 'MainType false');
		$this->assertEquals('commit', $oArguments->getSubType(), 'SubType false');
	} // function

	/**
	 * Test if values are returned correctly.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesPreCommit()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => TEST_SVN_EXAMPLE,
				  2 => '666-1',
				  3 => 'pre-commit',
				 );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
		$this->assertEquals(TEST_SVN_EXAMPLE, $oArguments->getRepository(), 'Repository false');
		$this->assertEquals('Example', $oArguments->getRepositoryName(), 'ReposName false');
		$this->assertEquals('666-1', $oArguments->getTransaction(), 'Transcatino false');
		$this->assertEquals('pre-commit', $oArguments->getMainHook(), 'MainHook false');
		$this->assertEquals('pre', $oArguments->getMainType(), 'MainType false');
		$this->assertEquals('commit', $oArguments->getSubType(), 'Subtype false');
	} // function

	/**
	 * Test if values are returned correctly..
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesPostCommit()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => TEST_SVN_EXAMPLE,
				  2 => 666,
				  3 => 'post-commit'
				 );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
		$this->assertEquals(TEST_SVN_EXAMPLE, $oArguments->getRepository(), 'Repository false');
		$this->assertEquals('Example', $oArguments->getRepositoryName(), 'ReposName false');
		$this->assertEquals(666, $oArguments->getRevision(), 'Revision false');
		$this->assertEquals('post-commit', $oArguments->getMainHook(), 'MainHook false');
		$this->assertEquals('post', $oArguments->getMainType(), 'MainType false');
		$this->assertEquals('commit', $oArguments->getSubType(), 'SubType false');
	} // function

	/**
	 * Test if values are returned correctly.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesPreLock()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => TEST_SVN_EXAMPLE,
				  2 => 'testuser',
				  3 => '/hookframework/trunk/Core/Hook.php',
				  4 => 'pre-lock'
				 );

		$oArguments = new Arguments($aData);

		$sHook = '/hookframework/trunk/Core/Hook.php';
		$this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
		$this->assertEquals(TEST_SVN_EXAMPLE, $oArguments->getRepository(), 'Repository false');
		$this->assertEquals('Example', $oArguments->getRepositoryName(), 'ReposName false');
		$this->assertEquals($sHook, $oArguments->getFile(), 'File false');
		$this->assertEquals('pre-lock', $oArguments->getMainHook(), 'Main Hook false');
		$this->assertEquals('pre', $oArguments->getMainType(), 'MainType false');
		$this->assertEquals('lock', $oArguments->getSubType(), 'SubType false');
	} // function

	/**
	 * Test if values are returned correctly.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesPreRevPropChange()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => TEST_SVN_EXAMPLE,
				  2 => 2009,
				  3 => 'testuser',
				  4 => 'ignore',
				  5 => 'A',
				  6 => 'pre-revprop-change',
				 );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
		$this->assertEquals(TEST_SVN_EXAMPLE, $oArguments->getRepository(), 'Repos path false');
		$this->assertEquals('Example', $oArguments->getRepositoryName(), 'ReposName false');
		$this->assertEQuals(2009, $oArguments->getRevision(), 'Revision false');
		$this->assertEquals('pre-revprop-change', $oArguments->getMainHook(), 'Main Hook false');
		$this->assertEquals('pre', $oArguments->getMainType(), 'Main Type false');
		$this->assertEquals('revprop-change', $oArguments->getSubType(), 'Sub Type false');
		$this->assertEquals('ignore', $oArguments->getPropertyName(), 'Property Name false');
		$this->assertEquals('A', $oArguments->getAction(), 'Action false');
	} // function

	/**
	 * Test if values are returned correctly.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetValuesPreRevPropChangeFalse()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => TEST_SVN_EXAMPLE,
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
	 * Test if values are returned correctly.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testSubActions1()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => TEST_SVN_EXAMPLE,
				  2 => '666-1',
				  3 => 'pre-commit',
				 );

		$aExpect = array(
					'commit', 'lock', 'revprop-change', 'unlock'
				   );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
		$this->assertEquals($aExpect, $oArguments->getSubActions(), 'Subaction false');
	} // function

	/**
	 * Test if values are returned correctly.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testSubActions2()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => TEST_SVN_EXAMPLE,
				  2 => 666,
				  3 => 'post-commit'
				 );

		$aExpect = array(
					'commit', 'lock', 'revprop-change', 'unlock'
				   );

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
		$this->assertEquals($aExpect, $oArguments->getSubActions(), 'Subaction false');
	} // function

	/**
	 * Test if values are returned correctly.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testSubActions3()
	{
		$aData = array(
				  0 => '/var/local/svn/hooks/Hook',
				  1 => TEST_SVN_EXAMPLE,
				  2 => 'testuser12',
				  3 => 'start-commit'
				 );

		$aExpect = array('commit');

		$oArguments = new Arguments($aData);

		$this->assertTrue($oArguments->argumentsOk(), 'Arguments false');
		$this->assertEquals($aExpect, $oArguments->getSubActions(), 'Subaction false');
	} // function
} // class
