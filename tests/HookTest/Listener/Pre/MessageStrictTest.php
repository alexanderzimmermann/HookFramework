<?php
/**
 * Argument Tests.
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

use Example\Pre\MessageStrict;

require_once __DIR__ . '/../../../Bootstrap.php';

require_once __DIR__ . '/../../../../Repositories/Example/Pre/MessageStrict.php';

/**
 * Argument Tests.
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
class MessageStrictTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test object message.
	 * @var MessageStrict
	 */
	private $oMessage;

	/**
	 * SetUp operations.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oMessage = new MessageStrict();
	} // function

	/**
	 * Test the comment listener strict, when no comment was given.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMessageStrictNoMessage()
	{
		$sUser    = 'zora';
		$sDate    = '2008-01-10 12:12:12';
		$sComment = '';
		$oInfo    = new Info('666-1', 666, $sUser, $sDate, $sComment);

		$this->oMessage->processAction($oInfo);

		$aResult = $oInfo->getErrorLines();

		$sMessage  = 'Please provide a comment for the commit' . "\n";
		$sMessage .= 'The comment should be like:' . "\n";
		$sMessage .= '+ If you add something.' . "\n";
		$sMessage .= '- If you delete something.' . "\n";
		$sMessage .= '* If you changed something.' . "\n";

		$aExpected = array($sMessage);

		$this->assertEquals($aExpected, $aResult);
	} // function

	/**
	 * Test, when comment is to short (like "fix", "fixed").
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMessageStrictMessageTooShort()
	{
		$sUser    = 'zora';
		$sDate    = '2008-01-10 12:12:12';
		$sComment = '* Fix';
		$oInfo    = new Info('666-1', 666, $sUser, $sDate, $sComment);

		$this->oMessage->processAction($oInfo);

		$aResult = $oInfo->getErrorLines();

		$sMessage1 = 'The comment should be more precisely!';

		$sMessage2  = 'Comment should contain whole sentences';
		$sMessage2 .= "\n" . 'Subject, Predicate, Object, Point!';

		$sMessage3  = 'Comment should contain whole sentences and more precisely.';
		$sMessage3 .= "\n" . 'Subject, Predicate, Object, Point!';

		$aExpected = array(
					  $sMessage1,
					  $sMessage2,
					  $sMessage3
					 );

		$this->assertEquals($aExpected, $aResult);
	} // function

	/**
	 * Data provider.
	 * @return array
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function getComments()
	{
		$sMessage1  = 'The comment should be like:' . "\n";
		$sMessage1 .= '+ If you add something.' . "\n";
		$sMessage1 .= '- If you delete something.' . "\n";
		$sMessage1 .= '* If you changed something.' . "\n";

		$sMessage2   = 'The comment should be more precisely!';
		$sMessage21  = 'Comment should contain whole sentences';
		$sMessage21 .= "\n" . 'Subject, Predicate, Object, Point!';

		$sMessage3  = 'Comment should contain whole sentences and more precisely.';
		$sMessage3 .= "\n" . 'Subject, Predicate, Object, Point!';

		return array(
				array(
				 'Test comment',
				 array(
				  $sMessage1,
				 )
				),
				array(
				 '* Comment',
				 array($sMessage2, $sMessage21)
				),
				array(
				 '* it is foo',
				 array($sMessage3)
				)
			   );
	} // function

	/**
	 * Test the comment listener strict.
	 * @param string $sComment  Test comment.
	 * @param array  $aExpected Expected messages.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @dataProvider getComments
	 */
	public function testMessageStrictError($sComment, array $aExpected)
	{
		$sUser = 'zora';
		$sDate = '2008-01-10 12:12:12';
		$oInfo = new Info('666-1', 666, $sUser, $sDate, $sComment);

		$this->oMessage->processAction($oInfo);

		$aResult = $oInfo->getErrorLines();

		$this->assertSame($aExpected, $aResult);
	} // function

	/**
	 * Test comment listener strict.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMessageStrictOk()
	{
		$sUser    = 'zora';
		$sDate    = '2008-01-10 12:12:12';
		$sComment = '* This is a correct comment sentence.';
		$oInfo    = new Info('666-1', 666, $sUser, $sDate, $sComment);

		$this->oMessage->processAction($oInfo);

		$aResult = $oInfo->getErrorLines();

		$this->assertTrue(empty($aResult));
	} // function
} // class
