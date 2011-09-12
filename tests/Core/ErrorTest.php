<?php
/**
 * Error-Objekt Tests.
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

require_once dirname(__FILE__) . '/../TestHelper.php';
require_once 'Core/Error.php';

/**
 * Error-Objekt Tests.
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
class ErrorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Error Objekt.
	 * @var Error
	 */
	private $oError;

	/**
	 * SetUp Operationen.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oError = new Error();
	} // function

	/**
	 * Testen, wenn kein Fehler aufgetreten ist.
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
	 * Testen für addError Methode.
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
	 * Testen Fehlerzeilen hinzufuegen.
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
	 * Testen fuer Error Handling eines Info Objektes.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testErrorInfo()
	{
		// Erzeugen der Objekte CommitInfo und CommitObject.
		$aFunctions = array(
					   'getObjectPath', 'getErrorLines'
					  );

		$sFile      = dirname(__FILE__) . '/_files/error_lines_info.txt';
		$sErrorInfo = file_get_contents($sFile);
		$aErrorInfo = explode("\n", $sErrorInfo);

		$aArguments = array(
					   array('/path/to/a/file/in/svn.txt'), $aErrorInfo
					  );

		$aArguments = array(
					   '666-1', 666, 'alexander', '21.12.2008', 'Test'
					  );

		$oInfo = $this->getMock('CommitInfo', $aFunctions, $aArguments);
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
	 * Testen 2.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testErrorObject()
	{
		// Erzeugen der Objekte CommitInfo und CommitObject.
		$aArguments = array(
					   '666-1', 666, 'alexander', '21.12.2008', 'Test'
					  );

		$oInfo = $this->getMock('CommitInfo', array(), $aArguments);

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

		$oObject = $this->getMock('CommitObject', $aFunctions, $aParams);
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
	 * Testen, wenn 2 Objekte Fehler haben.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testErrorObjectTwoObjects()
	{
		// Erzeugen der Objekte CommitInfo und CommitObject.
		$aArguments = array(
					   '666-1', 666, 'alexander', '21.12.2008', 'Test'
					  );

		$oInfo = $this->getMock('CommitInfo', array(), $aArguments);

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

		$oObject = $this->getMock('CommitObject', $aFunctions, $aParams);
		$oObject->expects($this->any())
				->method('getObjectPath')
				->will($this->returnValue('/path/to/a/file/in/svn.txt'));

		$oObject->expects($this->any())
				->method('getErrorLines')
				->will($this->returnValue($aErrorObject));

		$this->oError->processActionObject($oObject);

		// Eine zweite Datei simulieren.
		$oObject = $this->getMock('CommitObject', $aFunctions, $aParams);
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
