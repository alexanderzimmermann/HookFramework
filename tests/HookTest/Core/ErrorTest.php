<?php
/**
 * Error-Objekt Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Core;

use Hook\Core\Error;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Error-Object Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
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
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUp()
    {
        $this->oError = new Error();
    }

    /**
     * Test, when a error occurred.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testNoError()
    {
        $this->assertFalse($this->oError->hasCommonError(), 'Common error');
        $this->assertFalse($this->oError->hasError(), 'Error');

        $this->assertEquals('', $this->oError->getCommonMessages(), 'Common Messages');
        $this->assertEquals('', $this->oError->getMessages(), 'Messages');
    }

    /**
     * Test for addError method.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testAddError()
    {
        $sMessage = 'Simple Error Message';
        $this->oError->addError($sMessage);

        $this->assertTrue($this->oError->hasCommonError(), 'Common Error');
        $this->assertEquals($sMessage, $this->oError->getCommonMessages(), 'Message');
    }

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

        $this->assertTrue($this->oError->hasCommonError(), 'Common Error');

        $this->assertEquals($sMessage, $this->oError->getCommonMessages(), 'Common Messages');
    }

    /**
     * Test for error handling of a Info Object..
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testErrorInfo()
    {
        $sFile      = __DIR__ . '/_files/Error/lines_info.txt';
        $sErrorInfo = file_get_contents($sFile);
        $aErrorInfo = explode("\n", $sErrorInfo);

        $oInfo = $this->getMock('Hook\Commit\Info', array(), array(), '', false);
        $oInfo->expects($this->any())
              ->method('getObjectPath')
              ->will($this->returnValue('/path/to/a/file/in/svn.ext'));

        $oInfo->expects($this->any())
              ->method('getErrorLines')
              ->will($this->returnValue($aErrorInfo));

        $this->oError->setListener('ErrorTest Listener');
        $this->oError->processActionInfo($oInfo);

        $sExpected = __DIR__ . '/_files/Error/expected-' . __FUNCTION__  .'.txt';
        $sExpected = file_get_contents($sExpected);

        $this->assertEquals($sExpected, $this->oError->getMessages());
    }

    /**
     * Test 2.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testErrorObject()
    {
        // Commit Object.
        $sFile        = __DIR__ . '/_files/Error/lines_object.txt';
        $sFile        = file_get_contents($sFile);
        $aErrorObject = explode("\n", $sFile);

        $oObject = $this->getMock('Hook\Commit\Object', array(), array(), '', false);
        $oObject->expects($this->any())
                 ->method('getObjectPath')
                 ->will($this->returnValue('/path/to/a/file/in/svn.txt'));

        $oObject->expects($this->any())
                ->method('getErrorLines')
                ->will($this->returnValue($aErrorObject));

        $this->oError->setListener('ErrorTest Listener');
        $this->oError->processActionObject($oObject);

        // Tests.
        $this->assertTrue($this->oError->hasError(), 'getError not true.');

        $sExpected = __DIR__ . '/_files/Error/expected-' . __FUNCTION__  .'.txt';
        $sExpected = file_get_contents($sExpected);

        $this->assertEquals($sExpected, $this->oError->getMessages(), 'getMessage false.');
    }

    /**
     * Test, when 2 Objects contain errors.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testErrorObjectTwoObjects()
    {
        // Commit Object.
        $sFile        = __DIR__ . '/_files/Error/lines_object.txt';
        $sFile        = file_get_contents($sFile);
        $aErrorObject = explode("\n", $sFile);


        $oObject = $this->getMock('Hook\Commit\Object', array(), array(), '', false);
        $oObject->expects($this->any())
                ->method('getObjectPath')
                ->will($this->returnValue('/path/to/a/file/in/svn.txt'));

        $oObject->expects($this->any())
                ->method('getErrorLines')
                ->will($this->returnValue($aErrorObject));

        $this->oError->setListener('ErrorTest Listener 1');
        $this->oError->processActionObject($oObject);

        // Simulate a 2nd file.
        $oObject = $this->getMock('Hook\Commit\Object', array(), array(), '', false);
        $oObject->expects($this->any())
                ->method('getObjectPath')
                ->will($this->returnValue('/path/to/a/file/in/info.txt'));

        $oObject->expects($this->any())
                ->method('getErrorLines')
                ->will($this->returnValue($aErrorObject));

        $this->oError->setListener('ErrorTest Listener 2');
        $this->oError->processActionObject($oObject);

        // Asserts.
        $sExpected = __DIR__ . '/_files/Error/expected-' . __FUNCTION__  .'.txt';
        $sExpected = file_get_contents($sExpected);

        $this->assertTrue($this->oError->hasError(), 'getError not true.');
        $this->assertEquals($sExpected, $this->oError->getMessages(), 'Messages not equal.');
    }
}
