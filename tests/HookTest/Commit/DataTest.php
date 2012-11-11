<?php
/**
 * Commit Data Tests.
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

namespace HookTest\Core\Commit;

use Hook\Commit\Data;
use HookTest\Core\Commit\DataHelper;
use HookTest\Core\Commit\DataHelperDirs;
use HookTest\Core\Commit\DataHelperActionTypes;
use HookTest\Core\Commit\DataHelperExtensions;

require_once __DIR__ . '/../../Bootstrap.php';

require_once __DIR__ . '/DataHelper.php';
require_once __DIR__ . '/DataHelperDirs.php';
require_once __DIR__ . '/DataHelperActionTypes.php';
require_once __DIR__ . '/DataHelperExtensions.php';

/**
 * Commit Data Tests.
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
class DataTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Commit Data Object.
	 * @var Data
	 */
	private $oData;

	/**
	 * Set Up Method.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function setUp()
	{
		$this->oData = new Data();
	} // function

	/**
	 * Test add object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testCreateObjectFile()
	{
		$aInfos['txn']      = '74-1';
		$aInfos['rev']      = 74;
		$aInfos['user']     = 'Zabu';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* A message for this tests.';

		$this->oData->createInfo($aInfos);

		// A file object.
		$aParams = array(
					'txn'    => '74-1',
					'rev'    => 74,
					'action' => 'U',
					'item'   => 'file.txt',
					'real'   => 'file.txt',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array()
				   );

		$oObject = $this->oData->createObject($aParams);

		$this->assertEquals('Hook\Commit\Data\Object', get_class($oObject), 'Not object Object');
		$this->assertEquals('74-1', $oObject->getTransaction(), 'Txn wrong');
		$this->assertEquals('U', $oObject->getAction(), 'Action wrong');
		$this->assertEquals('file.txt', $oObject->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Test add directory object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testCreateObjectDirectory()
	{
		$aInfos['txn']      = '74-1';
		$aInfos['rev']      = 74;
		$aInfos['user']     = 'Zabu';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* A message for this tests.';

		$this->oData->createInfo($aInfos);

		// A directory object.
		$aParams = array(
					'txn'    => '74-1',
					'rev'    => 74,
					'action' => 'U',
					'item'   => '/path',
					'real'   => '/path',
					'isdir'  => true,
					'props'  => array(),
					'lines'  => array()
				   );

		$oObject = $this->oData->createObject($aParams);

		$this->assertEquals('Hook\Commit\Data\Object', get_class($oObject), 'class not Object');
		$this->assertEquals('/path', $oObject->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Test to give the correct files for the listener.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetObjectsForListener()
	{
		$aInfos['txn']      = '74-1';
		$aInfos['rev']      = 74;
		$aInfos['user']     = 'Zabu';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* A message for this tests.';

		$this->oData->createInfo($aInfos);

		// A directory object.
		$aParams = array(
					'txn'    => '74-1',
					'rev'    => 74,
					'action' => 'U',
					'item'   => '/path',
					'real'   => '/path',
					'isdir'  => true,
					'props'  => array(),
					'lines'  => array()
				   );

		$this->oData->createObject($aParams);

		// A file object.
		$aParams['item']   = '/path/file_1.php';
		$aParams['real']   = '/path/file_1.php';
		$aParams['action'] = 'A';
		$aParams['isdir']  = false;

		$this->oData->createObject($aParams);

		// A file object.
		$aParams['item'] = '/path/file_2.php';
		$aParams['real'] = '/path/file_2.php';

		$this->oData->createObject($aParams);

		// Test listener.
		$oListener = new DataHelper();

		$aFiles = $this->oData->getObjects($oListener);

		$this->assertTrue(is_array($aFiles), '$aFiles no array');
		$this->assertEquals(2, count($aFiles), '$aFiles count wrong');

		$this->assertEquals('/path/file_2.php', $aFiles[1]->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Test directories and files after action and extension.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetFilesWithDirs()
	{
		$aInfos['txn']      = '110-1';
		$aInfos['rev']      = 111;
		$aInfos['user']     = 'Zabu';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* A message for this tests.';

		$this->oData->createInfo($aInfos);

		// A directory object.
		$aParams = array(
					'txn'    => '110-1',
					'rev'    => 111,
					'action' => 'U',
					'item'   => '/path',
					'real'   => '/path',
					'isdir'  => true,
					'props'  => array(),
					'lines'  => array()
				   );
		$this->oData->createObject($aParams);

		// A file object.
		$aParams['item']   = '/path/file_1.php';
		$aParams['real']   = '/path/file_1.php';
		$aParams['action'] = 'A';
		$this->oData->createObject($aParams);

		// A file object.
		$aParams['item']   = '/path/file_2.php';
		$aParams['real']   = '/path/file_2.php';
		$this->oData->createObject($aParams);

		$oListener = new DataHelperDirs();

		$aFiles = $this->oData->getObjects($oListener);

		$this->assertTrue(is_array($aFiles), '$aFiles no array');
		$this->assertEquals(3, count($aFiles), 'count $aFiles wrong');

		$this->assertEquals('/path/file_2.php', $aFiles[1]->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Test return all actions, when Array in register method is empty.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetFilesActionTypes()
	{
		$aInfos['txn']      = '110-1';
		$aInfos['rev']      = 111;
		$aInfos['user']     = 'Zabu';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* A message for this tests.';

		$this->oData->createInfo($aInfos);

		// A directory object.
		$aParams = array(
					'txn'    => '110-1',
					'rev'    => 111,
					'action' => 'U',
					'item'   => '/path/file_0.php',
					'real'   => '/path/file_0.php',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array()
				   );

		$this->oData->createObject($aParams);

		// A file object
		$aParams['action'] = 'A';
		$aParams['item']   = '/path/file_1.php';
		$aParams['real']   = '/path/file_1.php';

		$this->oData->createObject($aParams);

		// A file object
		$aParams['action'] = 'D';
		$aParams['item']   = '/path/file_2.php';
		$aParams['real']   = '/path/file_2.php';

		$this->oData->createObject($aParams);

		$oListener = new DataHelperActionTypes();

		$aFiles = $this->oData->getObjects($oListener);

		$this->assertTrue(is_array($aFiles), '$aFiles no array');
		$this->assertEquals(3, count($aFiles), 'count $aFiles wrong');

		$this->assertEquals('/path/file_0.php', $aFiles[1]->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Test return all extensions, when register array is empty.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetFilesExtensions()
	{
		$aInfos['txn']      = '110-1';
		$aInfos['rev']      = 111;
		$aInfos['user']     = 'Zabu';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* A message for this tests.';

		$this->oData->createInfo($aInfos);

		// A file object
		$aParams = array(
					'txn'    => '110-1',
					'rev'    => 111,
					'action' => 'U',
					'item'   => '/path/file_0.txt',
					'real'   => '/path/file_0.txt',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array()
				   );

		$this->oData->createObject($aParams);

		// A file object
		$aParams['action'] = 'A';
		$aParams['item']   = '/path/file_1.php';
		$aParams['real']   = '/path/file_1.php';

		$this->oData->createObject($aParams);

		// A file object
		$aParams['action'] = 'A';
		$aParams['item']   = '/path/file_1.phtml';
		$aParams['real']   = '/path/file_1.phtml';

		$this->oData->createObject($aParams);

		// A file object
		$aParams['action'] = 'D';
		$aParams['item']   = '/path/file_2.phtml';
		$aParams['real']   = '/path/file_2.phtml';

		$this->oData->createObject($aParams);

		$oListener = new DataHelperExtensions();

		$aFiles = $this->oData->getObjects($oListener);

		$this->assertTrue(is_array($aFiles), '$aFile no aray');
		$this->assertEquals(3, count($aFiles), 'file count wrong');

		$this->assertEquals('/path/file_1.phtml', $aFiles[1]->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Test create commit info object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testCreateInfo()
	{
		$aInfos['txn']      = '74-1';
		$aInfos['rev']      = 74;
		$aInfos['user']     = 'Zabu';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* A message for this tests.';

		$this->oData->createInfo($aInfos);

		$oInfo = $this->oData->getInfo();

		$this->assertEquals('Hook\Commit\Data\Info', get_class($oInfo), 'wrong class');
		$this->assertEquals($aInfos['txn'], $oInfo->getTransaction(), 'txn wrong');
		$this->assertEquals($aInfos['rev'], $oInfo->getRevision(), 'rev wrong');
		$this->assertEquals($aInfos['user'], $oInfo->getUser(), 'user wrong');
		$this->assertEquals($aInfos['datetime'], $oInfo->getDateTime(), 'datetime wrong');
		$this->assertEquals($aInfos['message'], $oInfo->getMessage(), 'message wrong');
	} // function
} // class