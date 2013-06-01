<?php
/**
 * Argument Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Listener\Pre;

use Hook\Commit\Info;

use ExampleSvn\Pre\Message;

require_once __DIR__ . '/../../../Bootstrap.php';

require_once __DIR__ . '/../../../../Repositories/ExampleSvn/Pre/Message.php';

/**
 * Argument Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test object Message.
     * @var Message
     */
    private $oMessage;

    /**
     * SetUp operations.
     * @return void
     * @author Alexander Zimmermann <alex@zimmemann.com>
     */
    protected function setUp()
    {
        $this->oMessage = new Message();
    }

    /**
     * Test the comment listener strict, if no comment is given.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testMessageStrictNoMessage()
    {
        $sUser    = 'Ilana';
        $sDate    = '2008-01-10 12:12:12';
        $sComment = '';
        $oInfo    = new Info('666-1', 666, $sUser, $sDate, $sComment);

        $this->oMessage->processAction($oInfo);

        $aResult = $oInfo->getErrorLines();

        $sMessage = 'Please provide a comment to this commit and use it as follows:' . "\n";
        $sMessage .= '+ If something new is added.' . "\n";
        $sMessage .= '- If something is deleted.' . "\n";
        $sMessage .= '* If something is changed.' . "\n";

        $aExpected = array($sMessage);

        $this->assertEquals($aExpected, $aResult);
    }

    /**
     * Dataprovider.
     * @return array
     * @author Alexander Zimmermann <alex@zimmemann.com>
     */
    public static function getComments()
    {
        $sExpectedMessage = 'Please start the comment as follows:' . "\n";
        $sExpectedMessage .= '+ If something new is added.' . "\n";
        $sExpectedMessage .= '- If something is deleted.' . "\n";
        $sExpectedMessage .= '* If something is changed.' . "\n";

        $sExpectedMessage2 = 'The comment should be more precisely!';

        return array(
            array(
                '* Test comment',
                array()
            ),
            array(
                '+ Test comment',
                array()
            ),
            array(
                '- Test comment',
                array()
            ),
            array(
                '- Changes test comment',
                array()
            ),
            array(
                'Test comment',
                array($sExpectedMessage)
            ),
            array(
                '*Test comment',
                array($sExpectedMessage)
            ),
            array(
                '* Test with special characters: abr?\195?\188cken ^^',
                array()
            ),
            array(
                '* Fix',
                array($sExpectedMessage2)
            )
        );
    }

    /**
     * Test message comment listener.
     * @param string $sMessage    Commit text message.
     * @param array  $aErrorLines Lines with error messages.
     * @return void
     * @author       Alexander Zimmermann <alex@azimmermann.com>
     * @dataProvider getComments
     */
    public function testMessage($sMessage, array $aErrorLines)
    {
        $sUser = 'Ilana';
        $sDate = '2008-01-10 12:12:12';
        $oInfo = new Info('666-1', 666, $sUser, $sDate, $sMessage);

        $this->oMessage->processAction($oInfo);

        $this->assertSame($aErrorLines, $oInfo->getErrorLines());
    }
}
