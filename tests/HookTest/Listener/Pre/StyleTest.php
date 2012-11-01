<?php
/**
 * Style Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Listener\Pre;

use Hook\Commit\CommitInfo;
use Hook\Commit\CommitObject;

use Example\Pre\Style;

require_once __DIR__ . '/../../../Bootstrap.php';

require_once __DIR__ . '/../../../../Repositories/Example/Pre/Style.php';

/**
 * Style Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class StyleTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test object Style Listener.
	 * @var Style
	 */
	private $oStyleListener;

	/**
	 * SetUp operations.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oStyleListener = new Style();
	} // function

	/**
	 * Testen des Style Listener mit einer falschen Datei.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testListenerStyleWithErrorFile()
	{
		// Testdatei aus SVN, wenn diese nicht existiert Test skippen.
		$sFile = __DIR__ . '/_files/parse-error_file1.php';

		if (file_exists($sFile) === false)
		{
			$this->markTestSkipped('File ' . $sFile . ' not found!');
		} // if

		// Ist Pear Paket phpcs und der PEAR Standard zum Testen installiert?
		$aOutput = array();
		exec('phpcs --standard=PEAR ' . __FILE__, $aOutput);

		if (true === empty($aOutput))
		{
			$this->markTestSkipped('phpcs not installed!');
		} // if

		if (count($aOutput) === 1)
		{
			$sMsg = 'ERROR: the "PEAR" coding standard is not installed.';
			if (substr($aOutput[0], 0, 50) === $sMsg)
			{
				$this->markTestSkipped('PEAR Standard not installed!');
			} // if
		} // if

		// Wenn alles Ok, dann Test starten.
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

		$this->oStyleListener->processAction($oObject);

		// Aufraeumen.
		unlink($sTmpPath);

		$aData = $oObject->getErrorLines();

		$aLines[0]   = 'FOUND 13 ERROR(S) AND 1 WARNING(S) AFFECTING 9 LINE(S)';
		$aLines[1]   = ' 17 | ERROR   | Category name "library" is not valid; ';
		$aLines[1]  .= 'consider "Library"';
		$aLines[2]  = ' 38 | ERROR   | Constants must be uppercase; expected ';
		$aLines[2] .= 'TEST but found test';

		$this->assertFalse(empty($aData), '$aData is empty.');
		$this->assertEquals($aLines[0], $aData[0], 'Expected 13 errors.');
		$this->assertEquals($aLines[1], $aData[13], 'Missing error for line 17.');
		$this->assertEquals($aLines[2], $aData[24], 'Missing error for line 38,');
	} // function

	/**
	 * Testen des Style Listener mit einer perfekten Datei.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @depends testListenerStyleWithErrorFile
	 */
	public function testListenerStyleWithCorrectFile()
	{
		// Testdatei aus SVN, wenn diese nicht existiert Test skippen.
		$sFile = __DIR__ . '/_files/correct_file1.php';

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

		$this->oStyleListener->processAction($oObject);

		// Aufraeumen.
		unlink($sTmpPath);

		$aData = $oObject->getErrorLines();
		$this->assertTrue(empty($aData));
	} // function
} // class
