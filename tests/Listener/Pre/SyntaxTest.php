<?php
/**
 * Syntax Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Core/Listener/ListenerObjectAbstract.php';
require_once 'Core/Commit/CommitInfo.php';
require_once 'Core/Commit/CommitObject.php';
require_once 'Listener/Pre/Syntax.php';

/**
 * Syntax Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class SyntaxTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Testobjekt Syntax Listener.
	 * @var Message
	 */
	private $oSyntaxListener;

	/**
	 * SetUp Operationen.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oSyntaxListener = new Syntax();
	} // function

	/**
	 * Testen des Kommentar Listener.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testListenerSyntaxWithError()
	{
		// Testdatei aus SVN, wenn diese nicht existiert Test skippen.
		$sFile = dirname(__FILE__) . '/_files/parse-error_file1.php';

		if (file_exists($sFile) === false)
		{
			$this->markTestSkipped('File ' . $sFile . ' not found!');
		} // if

		$sUser = 'testuser';
		$sDate = '2008-12-30 12:22:23';
		$sMsg  = '* Kommentar zu diesem Commit';
		$oInfo = new CommitInfo('666-1', 666, $sUser, $sDate, $sMsg);

		$aParams = array(
					'txn'    => '666-1',
					'rev'    => 666,
					'action' => 'U',
					'item'   => $sFile,
					'isdir'  => false,
					'props'  => array(),
					'lines'  => null,
					'info'   => $oInfo
				   );

		$oObject  = new CommitObject($aParams);
		$sTmpPath = $oObject->getTmpObjectPath();

		copy($sFile, $sTmpPath);

		$this->oSyntaxListener->processAction($oObject);

		// Ergebnis Zeilen.
		$sL0  = 'Parse error: syntax error, unexpected T_STRING in ';
		$sL0 .= $sTmpPath . ' on line 38';
		$sL1  = 'Errors parsing ' . $sTmpPath;

		$aData = array(
				  $sL0, '', $sL1, ''
				 );

		// Aufraeumen.
		unlink($sTmpPath);

		$this->assertEquals($aData, $oObject->getErrorLines());
	} // function

	/**
	 * Testen des Syntax Listener wenn eine Datei ok ist.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testListenerSyntaxWithCorrectFile()
	{
		// Testdatei aus SVN, wenn diese nicht existiert Test skippen.
		$sFile = dirname(__FILE__) . '/_files/correct_file1.php';

		if (file_exists($sFile) === false)
		{
			$this->markTestSkipped('File ' . $sFile . ' not found!');
		} // if

		$sUser = 'testuser';
		$sDate = '2008-12-30 12:22:23';
		$sMsg  = '* Kommentar zu diesem Commit';
		$oInfo = new CommitInfo('666-1', 666, $sUser, $sDate, $sMsg);

		$aParams = array(
					'txn'    => '666-1',
					'rev'    => 666,
					'action' => 'U',
					'item'   => $sFile,
					'isdir'  => false,
					'props'  => array(),
					'lines'  => null,
					'info'   => $oInfo
				   );

		$oObject  = new CommitObject($aParams);
		$sTmpPath = $oObject->getTmpObjectPath();

		copy($sFile, $sTmpPath);

		$this->oSyntaxListener->processAction($oObject);

		// Aufraeumen.
		unlink($sTmpPath);

		$aData = $oObject->getErrorLines();
		$this->assertTrue(empty($aData));
	} // function
} // class
