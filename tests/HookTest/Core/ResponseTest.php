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

require_once HF_TEST_DIR . 'Bootstrap.php';

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
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUp()
    {
        $this->oResponse = new Response();
    }

    /**
     * Test check values on create
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testCreate()
    {
        $this->assertSame('no text', $this->oResponse->getText());
        $this->assertSame(1, $this->oResponse->getResult());
    }

    /**
     * Set response result to success.
     * @author Alexander Zimmermann <alex@azimmermann.com>
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
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSetWrongValueForResult()
    {
        $this->oResponse->setResult('abc123');

        $this->assertSame(1, $this->oResponse->getResult());
    }

    /**
     * Test the send output.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSend()
    {
        $sFile = HF_TEST_FILES_DIR . 'send.file';
        $rFile = fopen($sFile, 'w+');
        $this->oResponse = new Response($rFile);

        $sMsg = "Failure during commit!\nComment is missing";
        $this->oResponse->setText($sMsg);
        $this->oResponse->send();

        $this->assertFileEquals(__DIR__ . '/_files/expected.file', $sFile);
    }
}
