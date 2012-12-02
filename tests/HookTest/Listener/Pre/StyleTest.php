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

use Hook\Commit\Data\Info;
use Hook\Commit\Data\Object;

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
 * @version    Release: 2.1.0
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
	 * Configuration array.
	 * @var array
	 */
	private $aCfg;

	/**
	 * SetUp operations.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$aCfg = array(
				 'Filter'   => array(
								'Directory'            => array(
														   0 => 'Filter/Filtered/',
														  ),
								'Files'                => array(
														   0 => 'Filter/NotFiltered/FilteredFile.php',
														  ),
								'WhitelistDirectories' => array(
														   0 => 'Filter/Filtered/Whitelist/',
														  ),
								'WhitelistFiles'       => array(
														   0 => 'Filter/Filtered/WhiteFile.php',
														  ),
							   ),
				 'Standard' => 'PEAR',
				 'Style'    => array (
								'TabWidth' => '4',
							   ),
				);

		$this->aCfg = $aCfg;

		$this->oStyleListener = new Style();
		$this->oStyleListener->setConfiguration($aCfg);
	} // function

	/**
	 * Check that the pear package PHP_CodeSniffer is available and the "standard" is installed.
	 *
	 * If not mark the test skipped.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function checkCodeSniffer()
	{
		$aOutput = array();
		exec('phpcs --standard=' . $this->aCfg['Standard'] . ' ' . __FILE__, $aOutput);

		if (true === empty($aOutput))
		{
			$this->markTestSkipped('phpcs or pear standard not installed!');
		} // if

		if (count($aOutput) === 1)
		{
			$sMsg = 'ERROR: the "PEAR" coding standard is not installed.';
			if (substr($aOutput[0], 0, 50) === $sMsg)
			{
				$this->markTestSkipped('PEAR Standard not installed!');
			} // if
		} // if

		return $aOutput;
	} // function

	/**
	 * Test that the filters are stored correctly.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testHandleFilter()
	{
		// Set configuration,register and process the listener.
		$this->oStyleListener->register();

		$oFilter = $this->oStyleListener->getObjectFilter();

		// Shorter.
		$aCfg = $this->aCfg['Filter'];

		$this->assertSame($aCfg['Directory'], $oFilter->getFilteredDirectories(), 'Directory');
		$this->assertSame($aCfg['Files'], $oFilter->getFilteredFiles(), 'Files');
		$this->assertSame($aCfg['WhitelistDirectories'], $oFilter->getWhiteListDirectories(), 'WhitelistDirectories');
		$this->assertSame($aCfg['WhitelistFiles'], $oFilter->getWhiteListFiles(), 'WhitelistFiles');
	} // function

	/**
	 * Test the style listener with a "wrong" file.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testListenerStyleWithErrorFile()
	{
		$sFile = __DIR__ . '/_files/parse-error_file1.php';

		// Is PEAR Package phpcs and the PEAR Standard for test installed.
		$this->checkCodeSniffer();

		// PEAR is installed, then run style test.
		$sUser = 'Indira';
		$sDate = '2008-12-30 12:22:23';
		$sMsg  = '* Comment to this commit';
		$oInfo = new Info('666-1', 666, $sUser, $sDate, $sMsg);

		$aParams = array(
					'txn'    => '666-1',
					'rev'    => 666,
					'action' => 'U',
					'item'   => $sFile,
					'real'   => $sFile,
					'isdir'  => false,
					'props'  => array(),
					'lines'  => null,
					'info'   => $oInfo
				   );

		$oObject  = new Object($aParams);
		$sTmpPath = $oObject->getTmpObjectPath();

		copy($sFile, $sTmpPath);

		$this->oStyleListener->processAction($oObject);

		// Clean up.
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
	 * Test the style listener with a "perfect" file.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @depends testListenerStyleWithErrorFile
	 */
	public function testListenerStyleWithCorrectFile()
	{
		// Is PEAR Package phpcs and the PEAR Standard for test installed.
		$this->checkCodeSniffer();

		$sFile = __DIR__ . '/_files/correct_file1.php';

		$sUser = 'Indira';
		$sDate = '2008-12-30 12:22:23';
		$sMsg  = '* Comment to this commit';
		$oInfo = new Info('666-1', 666, $sUser, $sDate, $sMsg);

		$aParams = array(
					'txn'    => '666-1',
					'rev'    => 666,
					'action' => 'U',
					'item'   => $sFile,
					'real'   => $sFile,
					'isdir'  => false,
					'props'  => array(),
					'lines'  => null,
					'info'   => $oInfo
				   );

		$oObject  = new Object($aParams);
		$sTmpPath = $oObject->getTmpObjectPath();

		copy($sFile, $sTmpPath);

		$this->oStyleListener->processAction($oObject);

		// Clean up.
		unlink($sTmpPath);

		$aData = $oObject->getErrorLines();
		$this->assertTrue(empty($aData));
	} // function
} // class
