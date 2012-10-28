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

namespace CoreTest\Commit;

use Core\Arguments;
use Core\Svn;
use Core\Commit\CommitParser;
use CoreTest\Commit\CommitParserHelper;

require_once __DIR__ . '/../../Bootstrap.php';

// Argumenten-Objekt.
require_once 'Core/Arguments.php';

// Subversion Objekt.
require_once 'Core/Svn.php';

// Commit Parser Objekt.
require_once 'Core/Commit/CommitParser.php';

// Hilfsklasse fuer die Tests.
require_once 'Core/Commit/CommitParserHelper.php';

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
class CommitParserTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Commit Parser object.
	 * @var CommitParser
	 */
	private $oCommitParser;

	/**
	 * Svn object.
	 * @var Svn
	 */
	private $oSvn;

	/**
	 * Argument object.
	 * @var Argument
	 */
	private $oArgument;

	/**
	 * Set Up Method.
	 * @param array $aArguments Arguments.
	 * @return CommitData
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

		$this->oCommitParser = new CommitParser($this->oArgument, $this->oSvn);

		$oCommitData = $this->oCommitParser->getCommitDataObject();
		$this->assertEquals('Core\Commit\CommitData', get_class($oCommitData));

		$oCommitInfo = $oCommitData->getCommitInfo();
		$this->assertEquals('Core\Commit\CommitInfo', get_class($oCommitInfo));

		return $oCommitData;
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

		$oCommitData = $this->setUpCommit($aArguments);

		$oListener = new CommitParserHelper();
		$aFiles    = $oCommitData->getObjects($oListener);

		$this->assertEquals(3, count($aFiles), 'Count of files is wrong.');

		$sFile  = 'hookframework/trunk/tmp/newfolder1/';
		$sFile .= 'newfolder1_1/correct_file3.php';
		$this->assertEquals($sFile, $aFiles[2]->getObjectPath(), 'Filepath is wrong.');

		$oInfo = $aFiles[2]->getCommitInfo();
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

		$oCommitData = $this->setUpCommit($aArguments);

		$oListener = new CommitParserHelper();
		$aFiles    = $oCommitData->getObjects($oListener);

		$this->assertEquals(25, count($aFiles), 'Files count not correct.');

		// Dateieintraege.
		$sFile = 'hookframework/trunk/Core/Hook.php';
		$this->assertEquals($sFile, $aFiles[13]->getObjectPath(), 'Objectpath Hook.php wrong.');

		$sFile = 'hookframework/trunk/Core/CommitObject.php';
		$this->assertEquals($sFile, $aFiles[18]->getObjectPath(), 'Objectpath CommitObject wrong.');

		$this->assertEquals('D', $aFiles[18]->getAction(), 'Action not D');

		// Diff Lines einer Datei.
		$sFile = 'hookframework/trunk/Core/Commit/CommitObject.php';
		$this->assertEquals($sFile, $aFiles[3]->getObjectPath(), 'Objectpath 3 not correct');

		$this->assertEquals('A', $aFiles[3]->getAction(), 'Action A is wrong.');

		$sFile  = dirname(__FILE__) . '/../../';
		$sFile .= '_files/txn/110-1/diff_lines_commitobject.txt';

		$sDiffLines = file_get_contents($sFile);
		$aDiffLines = explode("\n", $sDiffLines);

		$aActualLines = $aFiles[3]->getChangedLines()->getLines();
		$this->assertEquals($aDiffLines, $aActualLines, 'Difflines noct correct.');

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

		$oCommitData = $this->setUpCommit($aArguments);

		$oListener = new CommitParserHelper();
		$aFiles    = $oCommitData->getObjects($oListener);

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

		$oCommitData = $this->setUpCommit($aArguments);

		$oListener = new CommitParserHelper();
		$aFiles    = $oCommitData->getObjects($oListener);

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

		$oCommitData = $this->setUpCommit($aArguments);

		$oListener = new CommitParserHelper();
		$aFiles    = $oCommitData->getObjects($oListener);

		$this->assertEquals(1, count($aFiles));
	} // function
} // class