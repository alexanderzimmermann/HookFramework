<?php
/**
 * Syntax Tests.
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

use Example\Pre\Syntax;

require_once __DIR__ . '/../../../Bootstrap.php';

require_once __DIR__ . '/../../../../Repositories/Example/Pre/Syntax.php';

/**
 * Syntax Tests.
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
class SyntaxTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test object Syntax Listener.
	 * @var Syntax
	 */
	private $oSyntaxListener;

	/**
	 * Temporary path.
	 * @var string
	 */
	private $sTmpPath;

	/**
	 * SetUp operations.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oSyntaxListener = new Syntax();
	} // function

	/**
	 * Tear Down.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function tearDown()
	{
		if (true === file_exists($this->sTmpPath))
		{
			unlink($this->sTmpPath);
		} // if
	} // function

	/**
	 * Test the comment listener.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testListenerSyntaxWithError()
	{
		// Test file from SVN.
		$sFile = __DIR__ . '/_files/parse-error_file1.php';

		$sUser = 'Enya';
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

		$oObject        = new Object($aParams);
		$this->sTmpPath = $oObject->getTmpObjectPath();

		copy($sFile, $this->sTmpPath);

		$this->oSyntaxListener->processAction($oObject);

		// Expected result lines.
		$aData = array('Errors parsing ' . $this->sTmpPath);

		$this->assertEquals($aData, $oObject->getErrorLines());
	} // function

	/**
	 * Test the Syntax Listener when file is ok.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testListenerSyntaxWithCorrectFile()
	{
		// Test file from SVN.
		$sFile = __DIR__ . '/_files/correct_file1.php';

		$sUser = 'enya';
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

		$oObject        = new Object($aParams);
		$this->sTmpPath = $oObject->getTmpObjectPath();

		copy($sFile, $this->sTmpPath);

		$this->oSyntaxListener->processAction($oObject);

		$aData = $oObject->getErrorLines();
		$this->assertTrue(empty($aData));
	} // function
} // class
