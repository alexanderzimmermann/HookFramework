<?php
/**
 * Commit Data Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Core/Commit/CommitData.php';

// CommitInfo.
require_once 'Core/Commit/CommitInfo.php';

// CommitObject.
require_once 'Core/Commit/CommitObject.php';

// Filterobekt.
require_once 'Core/Filter/Filter.php';

// Hilfsklasse fuer die Tests.
require_once 'Core/Commit/CommitDataHelper.php';

// Hilfsklasse fuer die Tests.
require_once 'Core/Commit/CommitDataHelperDirs.php';

// Hilfsklasse fuer die Tests.
require_once 'Core/Commit/CommitDataHelperActionTypes.php';

// Hilfsklasse fuer die Tests.
require_once 'Core/Commit/CommitDataHelperExtensions.php';

/**
 * Commit Data Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class CommitDataTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Commit Data Objekt.
	 * @var CommitData
	 */
	private $oCommitData;

	/**
	 * Set Up Methode.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function setUp()
	{
		$this->oCommitData = new CommitData();
	} // function

	/**
	 * Objekt hinzufuegen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testCreateObjectFile()
	{
		$aInfos['txn']      = '74-1';
		$aInfos['rev']      = 74;
		$aInfos['user']     = 'Benutzer';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* Eine Nachricht fuer die Tests.';

		$this->oCommitData->createCommitInfo($aInfos);

		// Eine Datei Objekt.
		$aParams = array(
					'txn'    => '74-1',
					'rev'    => 74,
					'action' => 'U',
					'item'   => 'file.txt',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array()
				   );

		$oObject = $this->oCommitData->createObject($aParams);

		$this->assertEquals('CommitObject', get_class($oObject), 'Not object CommitObject');
		$this->assertEquals('74-1', $oObject->getTransaction(), 'Txn wrong');
		$this->assertEquals('U', $oObject->getAction(), 'Action wrong');
		$this->assertEquals('file.txt', $oObject->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Objekt hinzufuegen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testCreateObjectDirectory()
	{
		$aInfos['txn']      = '74-1';
		$aInfos['rev']      = 74;
		$aInfos['user']     = 'Benutzer';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* Eine Nachricht fuer die Tests.';

		$this->oCommitData->createCommitInfo($aInfos);

		// Ein Verzeichnis Objekt.
		$aParams = array(
					'txn'    => '74-1',
					'rev'    => 74,
					'action' => 'U',
					'item'   => '/path',
					'isdir'  => true,
					'props'  => array(),
					'lines'  => array()
				   );

		$oObject = $this->oCommitData->createObject($aParams);

		$this->assertEquals('CommitObject', get_class($oObject), 'class not CommitObject');
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
		$aInfos['user']     = 'Benutzer';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* Eine Nachricht fuer die Tests.';

		$this->oCommitData->createCommitInfo($aInfos);

		// Ein Verzeichnis Objekt.
		$aParams = array(
					'txn'    => '74-1',
					'rev'    => 74,
					'action' => 'U',
					'item'   => '/path',
					'isdir'  => true,
					'props'  => array(),
					'lines'  => array()
				   );

		$this->oCommitData->createObject($aParams);

		// Ein Datei Objekt.
		$aParams['item']   = '/path/file_1.php';
		$aParams['action'] = 'A';
		$aParams['isdir']  = false;

		$this->oCommitData->createObject($aParams);

		// Ein Datei Objekt.
		$aParams['item'] = '/path/file_2.php';

		$this->oCommitData->createObject($aParams);

		// Test listener.
		$oListener = new CommitDataHelper();

		$aFiles = $this->oCommitData->getObjects($oListener);

		$this->assertTrue(is_array($aFiles), '$aFiles no array');
		$this->assertEquals(2, count($aFiles), '$aFiles count wrong');

		$this->assertEquals('/path/file_2.php', $aFiles[1]->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Test Verzeichnisse und Dateien nach Aktion und Extension zurueck geben.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetFilesWithDirs()
	{
		$aInfos['txn']      = '110-1';
		$aInfos['rev']      = 111;
		$aInfos['user']     = 'Benutzer';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* Eine Nachricht fuer die Tests.';

		$this->oCommitData->createCommitInfo($aInfos);

		// Ein Verzeichnis Objekt.
		$aParams = array(
					'txn'    => '110-1',
					'rev'    => 111,
					'action' => 'U',
					'item'   => '/path',
					'isdir'  => true,
					'props'  => array(),
					'lines'  => array()
				   );
		$oObject = $this->oCommitData->createObject($aParams);

		// Ein Datei Objekt.
		$aParams['item']   = '/path/file_1.php';
		$aParams['action'] = 'A';
		$oObject = $this->oCommitData->createObject($aParams);

		// Ein Datei Objekt.
		$aParams['item']   = '/path/file_2.php';
		$oObject = $this->oCommitData->createObject($aParams);

		$oListener = new CommitDataHelperDirs();

		$aFiles = $this->oCommitData->getObjects($oListener);

		$this->assertTrue(is_array($aFiles), '$aFiles no array');
		$this->assertEquals(3, count($aFiles), 'count $aFiles wrong');

		$this->assertEquals('/path/file_2.php', $aFiles[1]->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Test alle Actions zurueck geben wenn Array in register Methode leer.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetFilesActionTypes()
	{
		$aInfos['txn']      = '110-1';
		$aInfos['rev']      = 111;
		$aInfos['user']     = 'Benutzer';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* Eine Nachricht fuer die Tests.';

		$this->oCommitData->createCommitInfo($aInfos);

		// Ein Verzeichnis Objekt.
		$aParams = array(
					'txn'    => '110-1',
					'rev'    => 111,
					'action' => 'U',
					'item'   => '/path/file_0.php',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array()
				   );

		$oObject = $this->oCommitData->createObject($aParams);

		// Ein Datei Objekt.
		$aParams['action'] = 'A';
		$aParams['item']   = '/path/file_1.php';

		$oObject = $this->oCommitData->createObject($aParams);

		// Ein Datei Objekt.
		$aParams['action'] = 'D';
		$aParams['item']   = '/path/file_2.php';

		$oObject = $this->oCommitData->createObject($aParams);

		$oListener = new CommitDataHelperActionTypes();

		$aFiles = $this->oCommitData->getObjects($oListener);

		$this->assertTrue(is_array($aFiles), '$aFiles no array');
		$this->assertEquals(3, count($aFiles), 'count $aFiles wrong');

		$this->assertEquals('/path/file_0.php', $aFiles[1]->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Test alle Extensions zurueck geben wenn Array in register leer.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetFilesExtensions()
	{
		$aInfos['txn']      = '110-1';
		$aInfos['rev']      = 111;
		$aInfos['user']     = 'Benutzer';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* Eine Nachricht fuer die Tests.';

		$this->oCommitData->createCommitInfo($aInfos);

		// Ein Datei Objekt.
		$aParams = array(
					'txn'    => '110-1',
					'rev'    => 111,
					'action' => 'U',
					'item'   => '/path/file_0.txt',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array()
				   );

		$oObject = $this->oCommitData->createObject($aParams);

		// Ein Datei Objekt.
		$aParams['action'] = 'A';
		$aParams['item']   = '/path/file_1.php';

		$oObject = $this->oCommitData->createObject($aParams);

		// Ein Datei Objekt.
		$aParams['action'] = 'A';
		$aParams['item']   = '/path/file_1.phtml';

		$oObject = $this->oCommitData->createObject($aParams);

		// Ein Datei Objekt.
		$aParams['action'] = 'D';
		$aParams['item']   = '/path/file_2.phtml';

		$oObject = $this->oCommitData->createObject($aParams);

		$oListener = new CommitDataHelperExtensions();

		$aFiles = $this->oCommitData->getObjects($oListener);

		$this->assertTrue(is_array($aFiles), '$aFile no aray');
		$this->assertEquals(3, count($aFiles), 'file count wrong');

		$this->assertEquals('/path/file_1.phtml', $aFiles[1]->getObjectPath(), 'objectpath wrong');
	} // function

	/**
	 * Testen Commit Info Objekt erstellen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testCreateCommitInfo()
	{
		$aInfos['txn']      = '74-1';
		$aInfos['rev']      = 74;
		$aInfos['user']     = 'Benutzer';
		$aInfos['datetime'] = '2008-12-30 12:23:45';
		$aInfos['message']  = '* Eine Nachricht fuer die Tests.';

		$this->oCommitData->createCommitInfo($aInfos);

		$oInfo = $this->oCommitData->getCommitInfo();

		$this->assertEquals('CommitInfo', get_class($oInfo), 'wrong class');
		$this->assertEquals($aInfos['txn'], $oInfo->getTransaction(), 'txn wrong');
		$this->assertEquals($aInfos['rev'], $oInfo->getRevision(), 'rev wrong');
		$this->assertEquals($aInfos['user'], $oInfo->getUser(), 'user wrong');
		$this->assertEquals($aInfos['datetime'], $oInfo->getDateTime(), 'datetime wrong');
		$this->assertEquals($aInfos['message'], $oInfo->getMessage(), 'message wrong');
	} // function
} // class