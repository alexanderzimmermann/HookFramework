<?php
/**
 * Error-Objekt Tests.
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

use Core\Error;
use Core\Commit\CommitInfo;
use Core\Commit\CommitObject;

require_once __DIR__ . '/../Bootstrap.php';
require_once 'Core/Error.php';
require_once 'Core/Commit/CommitInfo.php';
require_once 'Core/Commit/CommitObject.php';

/**
 * Error-Object Tests.
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
class ErrorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Error Object.
	 * @var Error
	 */
	private $oError;

	/**
	 * SetUp Operations.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oError = new Error();
	} // function

	/**
	 * Test, when a error occurred.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testNoError()
	{
		$this->assertFalse($this->oError->getCommonError(), 'Common error');
		$this->assertFalse($this->oError->getError(), 'Error');

		$this->assertEquals('', $this->oError->getCommonMessages(), 'Common Messages');
		$this->assertEquals('', $this->oError->getMessages(), 'Messages');
	} // function

	/**
	 * Test for addError method.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testAddError()
	{
		$sMessage = 'Simple Error Message';
		$this->oError->addError($sMessage);

		$this->assertTrue($this->oError->getCommonError(), 'Common Error');
		$this->assertEquals($sMessage, $this->oError->getCommonMessages(), 'Message');
	} // function

	/**
	 * Test add error lines.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testAddErrorLines()
	{
		$aMessage[] = 'Simple Error Message Line 1';
		$aMessage[] = 'Simple Error Message Line 2';
		$aMessage[] = 'Simple Error Message Line 3';

		$sMessage = implode("\n", $aMessage);

		$this->oError->addErrorLines($aMessage);

		$this->assertTrue($this->oError->getCommonError(), 'Common Error');

		$this->assertEquals($sMessage, $this->oError->getCommonMessages(), 'Common Messages');
	} // function

	/**
	 * Test for error handling of a Info Object..
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testErrorInfo()
	{
		// Create objects for Objekte CommitInfo and CommitObject.
		$aFunctions = array(
					   'getObjectPath', 'getErrorLines'
					  );

		$sFile      = dirname(__FILE__) . '/_files/error_lines_info.txt';
		$sErrorInfo = file_get_contents($sFile);
		$aErrorInfo = explode("\n", $sErrorInfo);

		$aArguments = array(
					   '666-1', 666, 'alexander', '21.12.2008', 'Test'
					  );

		$oInfo = $this->getMock('Core\Commit\CommitInfo', $aFunctions, $aArguments);
		$oInfo->expects($this->any())
			  ->method('getObjectPath')
			  ->will($this->returnValue('/path/to/a/file/in/svn.ext'));

		$oInfo->expects($this->any())
			  ->method('getErrorLines')
			  ->will($this->returnValue($aErrorInfo));

		$sExpected  = "\n\n";
		$sExpected .= str_repeat('~', 80) . "\n";
		$sExpected .= "\n";
		$sExpected .= str_repeat('=', 80) . "\n";
		$sExpected .= $sErrorInfo;
		$sExpected .= "\n\n";
		$sExpected .= str_repeat('~', 80) . "\n";

		$this->oError->processActionInfo($oInfo);

		$this->assertEquals($sExpected, $this->oError->getMessages());
	} // function

	/**
	 * Test 2.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testErrorObject()
	{
		// Create objects for Objekte CommitInfo and CommitObject.
		$aArguments = array(
					   '666-1', 666, 'alexander', '21.12.2008', 'Test'
					  );

		$oInfo = $this->getMock('Core\Commit\CommitInfo', array(), $aArguments);

		// Commit Object Objekt.
		$sFile        = dirname(__FILE__) . '/_files/error_lines_object.txt';
		$sFile        = file_get_contents($sFile);
		$aErrorObject = explode("\n", $sFile);

		$aFunctions = array(
					   'getObjectPath', 'getErrorLines'
					  );

		$aParams = array(
					array(
					 'txn'    => '666-1',
					 'rev'    => 666,
					 'action' => 'U',
					 'item'   => '/path/to/file',
					 'isdir'  => false,
					 'info'   => $oInfo,
					 'props'  => array(),
					 'lines'  => array()
					)
				   );

		$oObject = $this->getMock('Core\Commit\CommitObject', $aFunctions, $aParams);
		$oObject->expects($this->any())
				->method('getObjectPath')
				->will($this->returnValue('/path/to/a/file/in/svn.txt'));

		$oObject->expects($this->any())
				->method('getErrorLines')
				->will($this->returnValue($aErrorObject));

		$this->oError->processActionObject($oObject);

		// Tests.
		$this->assertTrue($this->oError->getError(), 'getError not true.');

		$sExpected  = "\n\n" . str_repeat('~', 80) . "\n";
		$sExpected .= '/path/to/a/file/in/svn.txt' . "\n";
		$sExpected .= str_repeat('-', 80) . "\n\n";
		$sExpected .= str_repeat('=', 80) . "\n";
		$sExpected .= $sFile;
		$sExpected .= "\n\n";
		$sExpected .= str_repeat('~', 80) . "\n";

		$this->assertEquals($sExpected, $this->oError->getMessages(), 'getMessage false.');
	} // function

	/**
	 * Test, when 2 Objects contain errors.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testErrorObjectTwoObjects()
	{
		// Create objects for Objekte CommitInfo and CommitObject.
		$aArguments = array(
					   '666-1', 666, 'alexander', '21.12.2008', 'Test'
					  );

		$oInfo = $this->getMock('Core\Commit\CommitInfo', array(), $aArguments);

		// Commit Object.
		$sFile        = dirname(__FILE__) . '/_files/error_lines_object.txt';
		$sFile        = file_get_contents($sFile);
		$aErrorObject = explode("\n", $sFile);

		$aFunctions = array(
					   'getObjectPath', 'getErrorLines'
					  );

		$aParams = array(
					array(
					 'txn'    => '666-1',
					 'rev'    => 666,
					 'action' => 'U',
					 'item'   => '/path/to/file',
					 'isdir'  => false,
					 'info'   => $oInfo,
					 'props'  => array(),
					 'lines'  => array()
					)
				   );

		$oObject = $this->getMock('Core\Commit\CommitObject', $aFunctions, $aParams);
		$oObject->expects($this->any())
				->method('getObjectPath')
				->will($this->returnValue('/path/to/a/file/in/svn.txt'));

		$oObject->expects($this->any())
				->method('getErrorLines')
				->will($this->returnValue($aErrorObject));

		$this->oError->processActionObject($oObject);

		// Simulate a 2nd file.
		$oObject = $this->getMock('Core\Commit\CommitObject', $aFunctions, $aParams);
		$oObject->expects($this->any())
				->method('getObjectPath')
				->will($this->returnValue('/path/to/a/file/in/info.txt'));

		$oObject->expects($this->any())
				->method('getErrorLines')
				->will($this->returnValue($aErrorObject));

		$this->oError->processActionObject($oObject);

		// Tests.
		$this->assertTrue($this->oError->getError(), 'getError not true.');

		$sExpected  = "\n\n" . str_repeat('~', 80) . "\n";
		$sExpected .= '/path/to/a/file/in/svn.txt' . "\n";
		$sExpected .= str_repeat('-', 80) . "\n\n";
		$sExpected .= str_repeat('=', 80) . "\n";
		$sExpected .= $sFile;
		$sExpected .= "\n\n";

		$sExpected .= '/path/to/a/file/in/info.txt' . "\n";
		$sExpected .= str_repeat('-', 80) . "\n\n";
		$sExpected .= str_repeat('=', 80) . "\n";
		$sExpected .= $sFile;
		$sExpected .= "\n\n";
		$sExpected .= str_repeat('~', 80) . "\n";

		$this->assertEquals($sExpected, $this->oError->getMessages(), 'Messages not equal.');
	} // function
} // class
