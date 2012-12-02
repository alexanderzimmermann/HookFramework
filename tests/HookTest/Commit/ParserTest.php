<?php
/**
 * Parser Tests.
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

use Hook\Core\Arguments;
use Hook\Core\Svn;
use Hook\Commit\Data;
use Hook\Commit\Parser;
use HookTest\Core\Commit\ParserHelper;

require_once __DIR__ . '/../../Bootstrap.php';

require_once __DIR__ . '/ParserHelper.php';

/**
 * Parser Tests.
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
class ParserTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Commit Parser object.
	 * @var Parser
	 */
	private $oParser;

	/**
	 * Svn object.
	 * @var Svn
	 */
	private $oSvn;

	/**
	 * Argument object.
	 * @var Arguments
	 */
	private $oArgument;

	/**
	 * Set Up Method.
	 * @param array $aArguments Arguments.
	 * @return Data
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function setUpCommit(array $aArguments)
	{
		// Create Argument object.
		$this->oArgument = new Arguments($aArguments);

		$this->assertTrue($this->oArgument->argumentsOk());

		// Init Svn object.
		$this->oSvn = new Svn(TEST_SVN_BIN);
		$this->oSvn->init($this->oArgument);

		$this->oParser = new Parser($this->oArgument, $this->oSvn);

		$oData = $this->oParser->getCommitDataObject();
		$this->assertEquals('Hook\Commit\Data', get_class($oData));

		$oInfo = $oData->getInfo();
		$this->assertEquals('Hook\Commit\Data\Info', get_class($oInfo));

		return $oData;
	} // function

	/**
	 * Pre Commit Test.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testPreCommit74Ok()
	{
		$aArguments = array(
					   '/var/local/svn/hooks/Hook',
					   TEST_SVN_EXAMPLE,
					   '74-1',
					   'pre-commit'
					  );

		$oData = $this->setUpCommit($aArguments);

		// Mock Listener.
		$oListener = new ParserHelper();
		$aFiles    = $oData->getObjects($oListener);

		$this->assertEquals(3, count($aFiles), 'Count of files is wrong.');

		$sFile  = 'hookframework/trunk/tmp/newfolder1/';
		$sFile .= 'newfolder1_1/correct_file3.php';
		$this->assertEquals($sFile, $aFiles[2]->getObjectPath(), 'Filepath is wrong.');

		$oInfo = $aFiles[2]->getInfo();
		$sMsg  = $oInfo->getMessage();

		$sExpected  = '* Testcommit mit 3 Dateien und zwei ';
		$sExpected .= 'geänderten Properties.';
		$this->assertEquals($sExpected, $sMsg, 'Commit message wrong.');
	} // function

	/**
	 * Pre- Commit Test mit mehreren Dateien und Verzeichnissen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testPreCommit110MultipleData()
	{
		$aArguments = array(
					   '/var/local/svn/hooks/Hook',
					   TEST_SVN_EXAMPLE,
					   '110-1',
					   'pre-commit'
					  );

		$oData = $this->setUpCommit($aArguments);

		$oListener = new ParserHelper();
		$aFiles    = $oData->getObjects($oListener);

		$this->assertEquals(25, count($aFiles), 'Files count not correct.');

		// File entries.
		$sFile = 'hookframework/trunk/Core/Hook.php';
		$this->assertEquals($sFile, $aFiles[13]->getObjectPath(), 'Objectpath Hook.php wrong.');

		$sFile = 'hookframework/trunk/Core/CommitObject.php';
		$this->assertEquals($sFile, $aFiles[18]->getObjectPath(), 'Objectpath Object wrong.');

		$this->assertEquals('D', $aFiles[18]->getAction(), 'Action not D');

		// Diff Lines of a file
		$sFile = 'hookframework/trunk/Core/Commit/CommitObject.php';
		$this->assertEquals($sFile, $aFiles[3]->getObjectPath(), 'Objectpath 3 not correct');

		$this->assertEquals('A', $aFiles[3]->getAction(), 'Action A is wrong.');

		$aActualLines = $aFiles[3]->getChangedParts();
		$this->assertEquals(2, count($aActualLines), 'Difflines not correct.');

		// Check the properties.
		$aProperties = $aFiles[3]->getChangedProperties();
		$this->assertSame(1, count($aProperties), 'More or less than one property.');

		$oProperty = $aProperties['svn:keywords'];
		$this->assertSame('Id', $oProperty->getNewValue(), 'New value wrong.');
		$this->assertNull($oProperty->getOldValue(), 'Old value not null.');
		$this->assertSame('svn:keywords', $oProperty->getPropertyName(), 'Property name wrong.');
	} // function

	/**
	 * Pre Commit Test Binary.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testPreCommitTxn1719Binary()
	{
		$aArguments = array(
					   '/var/local/svn/hooks/Hook',
					   TEST_SVN_EXAMPLE,
					   '1719-1',
					   'pre-commit'
					  );

		$oData = $this->setUpCommit($aArguments);

		$oListener = new ParserHelper();
		$aFiles    = $oData->getObjects($oListener);

		$this->assertEquals(1, count($aFiles), 'Count of files is wrong.');

		$sFile = 'trunk/doc/db_scheme/db_scheme.mwb';
		$this->assertEquals($sFile, $aFiles[0]->getObjectPath(), 'Filepath is wrong.');
	} // function

	/**
	 * Pre Commit Test Loeschen einer Datei.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testPreCommitTxn1748DeleteFile()
	{
		$aArguments = array(
					   '/var/local/svn/hooks/Hook',
					   TEST_SVN_EXAMPLE,
					   '1748-1',
					   'pre-commit'
					  );

		$oData = $this->setUpCommit($aArguments);

		$oListener = new ParserHelper();
		$aFiles    = $oData->getObjects($oListener);

		$this->assertEquals(1, count($aFiles), 'Count of files is wrong.');

		$sFile = 'trunk/application/core/conf/controllers.inc.php';
		$this->assertEquals($sFile, $aFiles[0]->getObjectPath(), 'Filepath is wrong.');
	} // function

	/**
	 * Pre Commit Test Property Set.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testPreCommitTxn1749PropertyIgnore()
	{
		$aArguments = array(
					   '/var/local/svn/hooks/Hook',
					   TEST_SVN_EXAMPLE,
					   '1749-1',
					   'pre-commit'
					  );

		$oData = $this->setUpCommit($aArguments);

		$oListener = new ParserHelper();
		$aFiles    = $oData->getObjects($oListener);

		$this->assertEquals(1, count($aFiles));
	} // function
} // class