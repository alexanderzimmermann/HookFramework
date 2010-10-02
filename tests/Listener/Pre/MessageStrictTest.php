<?php
/**
 * Argument Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Core/Listener/ListenerInfoAbstract.php';
require_once 'Core/Commit/CommitInfo.php';
require_once 'Listener/Pre/MessageStrict.php';

/**
 * Argument Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class MessageStrictTest extends PHPUnit_Framework_TestCase
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
		$this->oMessage = new MessageStrict();
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
	 * Testen wenn der Kommentar zu wenig ist.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMessageStrictMessageTooShort()
	{
		$sUser    = 'testuser';
		$sDate    = '2008-01-10 12:12:12';
		$sComment = '* Fix';
		$oInfo    = new CommitInfo('666-1', 666, $sUser, $sDate, $sComment);

		$this->oMessage->processAction($oInfo);

		$aResult = $oInfo->getErrorLines();

		$sMessage1 = 'Der Kommentar sollte aussagekraeftig sein!';

		$sMessage2  = 'Der Kommentar sollte bitte vollstaendige Saetze ';
		$sMessage2 .= 'enthalten.' . "\n";
		$sMessage2 .= 'Subjekt, Praedikat, Objekt, Punkt!';

		$sMessage3  = 'Der Kommentar sollte bitte vollstaendige Saetze';
		$sMessage3 .= ' und gute Wortformulierungen enthalten.' . "\n";
		$sMessage3 .= 'Subjekt, Praedikat, Objekt, Punkt!';

		$aExpected = array(
					  $sMessage1,
					  $sMessage2,
					  $sMessage3
					 );

		$this->assertEquals($aExpected, $aResult);
	} // function

	/**
	 * Dataprovider.
	 * @return array
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function getComments()
	{
		$sMessage1  = 'Kommentar bitte wie folgt einleiten:' . "\n";
		$sMessage1 .= '+ Wenn etwas neues hinzugefuegt wird.' . "\n";
		$sMessage1 .= '- Wenn etwas entfernt wird.' . "\n";
		$sMessage1 .= '* Bei Aenduerungen der Datei.' . "\n";

		$sMessage2  = 'Der Kommentar sollte bitte vollstaendige Saetze ';
		$sMessage2 .= 'enthalten.' . "\n";
		$sMessage2 .= 'Subjekt, Praedikat, Objekt, Punkt!';

		$sMessage3  = 'Der Kommentar sollte bitte vollstaendige Saetze';
		$sMessage3 .= ' und gute Wortformulierungen enthalten.' . "\n";
		$sMessage3 .= 'Subjekt, Praedikat, Objekt, Punkt!';

		return array(
				array(
				 'Testkommentar',
				 array(
				  $sMessage1,
				  $sMessage2
				 )
				),
				array(
				 '* Testkommentar',
				 array($sMessage2)
				),
				array(
				 '* it is foo',
				 array($sMessage3)
				)
			   );
	} // function

	/**
	 * Testen des Kommentar Listener Strict.
	 * @param string $sComment  Testkommentar.
	 * @param array  $aExpected Erwartete Fehlermeldung.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @dataProvider getComments
	 */
	public function testMessageStrictError($sComment, array $aExpected)
	{
		$sUser = 'testuser';
		$sDate = '2008-01-10 12:12:12';
		$oInfo = new CommitInfo('666-1', 666, $sUser, $sDate, $sComment);

		$this->oMessage->processAction($oInfo);

		$aResult = $oInfo->getErrorLines();

		$this->assertFalse(empty($aResult));
		$this->assertSame($aExpected, $aResult);
	} // function

	/**
	 * Testen des Kommentar Listener Strict.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMessageStrictOk()
	{
		$sUser    = 'testuser';
		$sDate    = '2008-01-10 12:12:12';
		$sComment = '* Das ist ein Kommentar der in Ordnung ist.';
		$oInfo    = new CommitInfo('666-1', 666, $sUser, $sDate, $sComment);

		$this->oMessage->processAction($oInfo);

		$aResult = $oInfo->getErrorLines();

		$this->assertTrue(empty($aResult));
	} // function
} // class
