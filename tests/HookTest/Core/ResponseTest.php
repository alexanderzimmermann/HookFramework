<?php
/**
 * Tests for response object.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace HookTest\Core;

use Hook\Core\Response;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Tests for response object.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Response object.
     * @var Response
     */
    protected $oResponse;

    /**
     * Setup
     * @return void
     */
    protected function setUp()
    {
        $this->oResponse = new Response();
    }

    /**
     * Test check values on create.
     * @return void
     */
    public function testCreate()
    {
        $this->assertSame('no response text given or exit is ok.', $this->oResponse->getText());
        $this->assertSame(1, $this->oResponse->getResult());
    }

    /**
     * Set response result to success.
     * @return void
     */
    public function testSetSuccess()
    {
        $this->oResponse->setResult(0);
        $this->oResponse->setText('');

        $this->assertSame(0, $this->oResponse->getResult());
        $this->assertSame('', $this->oResponse->getText());
    }

    /**
     * Set a wrong value for result.
     * @return void
     */
    public function testSetWrongValueForResult()
    {
        $this->oResponse->setResult('abc123');

        $this->assertSame(1, $this->oResponse->getResult());
    }

    /**
     * Test for error handling of a info object..
     * @return void
     */
    public function testErrorInfo()
    {
        $sFile      = __DIR__ . '/_files/Response/lines_info.txt';
        $sErrorInfo = file_get_contents($sFile);
        $aErrorInfo = explode("\n", $sErrorInfo);

        $oInfo = $this->getMock('Hook\Commit\Info', array(), array(), '', false);
        $oInfo->expects($this->any())
              ->method('getObjectPath')
              ->will($this->returnValue('/path/to/a/file/in/svn.ext'));

        $oInfo->expects($this->any())
              ->method('getErrorLines')
              ->will($this->returnValue($aErrorInfo));

        $oListener = $this->getMock('Hook\Listener\AbstractInfo');
        $oListener->expects($this->once())
                  ->method('getListenerName')
                  ->will($this->returnValue('ErrorTest Listener'));

        $this->oResponse->processActionInfo($oInfo, $oListener);

        $sExpected = __DIR__ . '/_files/Response/expected-' . __FUNCTION__  .'.txt';
        $sExpected = file_get_contents($sExpected);

        $this->assertEquals($sExpected, $this->oResponse->getMessages());
    }

    /**
     * Test for error handling of a file object.
     * @return void
     */
    public function testErrorObject()
    {
        // Commit Object.
        $sFile        = __DIR__ . '/_files/Response/lines_object.txt';
        $sFile        = file_get_contents($sFile);
        $aErrorObject = explode("\n", $sFile);

        $oObject = $this->getMock('Hook\Commit\Object', array(), array(), '', false);
        $oObject->expects($this->any())
                 ->method('getObjectPath')
                 ->will($this->returnValue('/path/to/a/file/in/svn.txt'));

        $oObject->expects($this->any())
                ->method('getErrorLines')
                ->will($this->returnValue($aErrorObject));

        $oListener = $this->getMock('Hook\Listener\AbstractObject');
        $oListener->expects($this->once())
                  ->method('getListenerName')
                  ->will($this->returnValue('ErrorTest Listener'));

        $this->oResponse->processActionObject($oObject, $oListener);

        // Tests.
        $this->assertSame(1, $this->oResponse->getResult(), 'Result not true.');

        $sExpected = __DIR__ . '/_files/Response/expected-' . __FUNCTION__  .'.txt';
        $sExpected = file_get_contents($sExpected);

        $this->assertEquals($sExpected, $this->oResponse->getMessages(), 'getMessage false.');
    }

    /**
     * Test, when 2 Objects contain errors.
     * @return void
     */
    public function testErrorObjectTwoObjects()
    {
        // Commit Object.
        $sFile        = __DIR__ . '/_files/Response/lines_object.txt';
        $sFile        = file_get_contents($sFile);
        $aErrorObject = explode("\n", $sFile);


        $oObject = $this->getMock('Hook\Commit\Object', array(), array(), '', false);
        $oObject->expects($this->any())
                ->method('getObjectPath')
                ->will($this->returnValue('/path/to/a/file/in/svn.txt'));

        $oObject->expects($this->any())
                ->method('getErrorLines')
                ->will($this->returnValue($aErrorObject));

        $oListener = $this->getMock('Hook\Listener\AbstractObject');
        $oListener->expects($this->once())
                  ->method('getListenerName')
                  ->will($this->returnValue('ErrorTest Listener 1'));

        $this->oResponse->processActionObject($oObject, $oListener);

        // Simulate a 2nd file.
        $oObject = $this->getMock('Hook\Commit\Object', array(), array(), '', false);
        $oObject->expects($this->any())
                ->method('getObjectPath')
                ->will($this->returnValue('/path/to/a/file/in/info.txt'));

        $oObject->expects($this->any())
                ->method('getErrorLines')
                ->will($this->returnValue($aErrorObject));

        $oListener = $this->getMock('Hook\Listener\AbstractObject');
        $oListener->expects($this->once())
                  ->method('getListenerName')
                  ->will($this->returnValue('ErrorTest Listener 2'));

        $this->oResponse->processActionObject($oObject, $oListener);

        // Asserts.
        $sExpected = __DIR__ . '/_files/Response/expected-' . __FUNCTION__  .'.txt';
        $sExpected = file_get_contents($sExpected);

        $this->assertSame(1, $this->oResponse->getResult(), 'Result not true.');
        $this->assertEquals($sExpected, $this->oResponse->getMessages(), 'Messages not equal.');
    }

    /**
     * Test the send output.
     */
    public function testSend()
    {
        $rFile = fopen('php://memory', 'w+');
        $this->oResponse = new Response($rFile);

        $sMsg = "Failure during commit!\nComment is missing";
        $this->oResponse->setText($sMsg);
        $this->oResponse->send();

        rewind($rFile);
        $aExpected = file_get_contents(__DIR__ . '/_files/Response/expected.file');
        $this->assertSame($aExpected, stream_get_contents($rFile));
    }
}
