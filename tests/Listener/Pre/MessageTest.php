<?php
/**
 * Argument Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Core/Listener/ListenerInfoAbstract.php';
require_once 'Core/Commit/CommitInfo.php';
require_once 'Listener/Pre/Message.php';

/**
 * Argument Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class MessageTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Testobjekt Message.
	 * @var Message
	 */
	private $oMessage;

	/**
	 * SetUp Operationen.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oMessage = new Message();
	} // function

	/**
	 * Testen des Kommentar Listener Strict wenn kein Kommentar vorhanden ist.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMessageStrictNoMessage()
	{
		$sUser    = 'testuser';
		$sDate    = '2008-01-10 12:12:12';
		$sComment = '';
		$oInfo    = new CommitInfo('666-1', 666, $sUser, $sDate, $sComment);

		$this->oMessage->processAction($oInfo);

		$aResult = $oInfo->getErrorLines();

		$sMessage  = 'Bitte einen Kommentar angeben und den ';
		$sMessage .= 'Kommentar bitte wie folgt einleiten:' . "\n";
		$sMessage .= '+ Wenn etwas neues hinzugefuegt wird.' . "\n";
		$sMessage .= '- Wenn etwas entfernt wird.' . "\n";
		$sMessage .= '* Bei Aenduerungen der Datei.' . "\n";

		$aExpected = array($sMessage);

		$this->assertEquals($aExpected, $aResult);
	} // function

	/**
	 * Dataprovider.
	 * @return array
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function getComments()
	{
		$sExpectedMessage  = 'Kommentar bitte wie folgt einleiten:' . "\n";
		$sExpectedMessage .= '+ Wenn etwas neues hinzugefuegt wird.' . "\n";
		$sExpectedMessage .= '- Wenn etwas entfernt wird.' . "\n";
		$sExpectedMessage .= '* Bei Aenduerungen der Datei.' . "\n";

		$sExpectedMessage2 = 'Der Kommentar sollte aussagekraeftig sein!';

		return array(
				array(
				 '* Testkommentar',
				 array()
				),
				array(
				 '+ Testkommentar',
				 array()
				),
				array(
				 '- Testkommentar',
				 array()
				),
				array(
				 '- Änderungen Testkommentar',
				 array()
				),
				array(
				 'Testkommentar',
				 array($sExpectedMessage)
				),
				array(
				 '*Testkommentar',
				 array($sExpectedMessage)
				),
				array(
				 '* Test mit Sonderzeichen: abr?\195?\188cken ^^',
				 array()
				),
				array(
				 '* Fix',
				 array($sExpectedMessage2)
				)
			   );
	} // function

	/**
	 * Testen des Kommentar Listener.
	 * @param string $sMessage    Commit Text Meldung.
	 * @param array  $aErrorLines Zeilen Fehlermeldungen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @dataProvider getComments
	 */
	public function testMessage($sMessage, array $aErrorLines)
	{
		$sUser = 'testuser';
		$sDate = '2008-01-10 12:12:12';
		$oInfo = new CommitInfo('666-1', 666, $sUser, $sDate, $sMessage);

		$this->oMessage->processAction($oInfo);

		$this->assertSame($aErrorLines, $oInfo->getErrorLines());
	} // function
} // class
