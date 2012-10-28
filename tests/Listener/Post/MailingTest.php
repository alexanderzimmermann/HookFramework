<?php
/**
 * Mailing Test.
 * @category   Tests
 * @package    Listener
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

use Example\Post\Mailing;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Mailing Test.
 * @category   Tests
 * @package    Listener
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class MailingTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Testobjekt fuer Mailing.
	 * @var Mailing
	 */
	private $oMailingListener;

	/**
	 * SetUp Operationen.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oMailingListener = new Mailing();
	} // function

	/**
	 * Testen ob das Objekt erzeugt wurde.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testObject()
	{
		$sClass = get_class($this->oMailingListener);
		$this->assertEquals('Mailing', $sClass);
	} // function

	/**
	 * Testen des Formats der Mail.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMailBodyI()
	{
		// Info- Objekt erzeugen.
		$sUser = 'testuser';
		$sDate = '2008-12-30 14:56:23';
		$sText = '* Testkommentar fuer die Unit Tests.';

		$oInfo = new CommitInfo('666-1', 666, $sUser, $sDate, $sText);

		// Testdaten fuer Dateien.
		$aParams = array(
					'txn'    => '',
					'rev'    => 666,
					'action' => '',
					'item'   => '',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array(),
					'info'   => $oInfo
				   );

		// Dateiobjekte erzeugen.
		$aObjects = array();

		$aParams['action'] = 'U';
		$aParams['item']   = '/hookframework/Core/Commit/CommitBase.php';
		$aObjects[]        = new CommitObject($aParams);

		$aParams['action'] = 'U';
		$aParams['item']   = '/hookframework/Core/Commit/CommitObject.php';
		$aObjects[]        = new CommitObject($aParams);

		$aParams['action'] = 'A';
		$aParams['item']   = '/hookframework/doc/hooktemplates/';
		$aParams['isdir']  = true;
		$aObjects[]        = new CommitObject($aParams);

		$aParams['action'] = 'A';
		$aParams['item']   = '/hookframework/doc/hooktemplates/pre-commit';
		$aParams['isdir']  = false;
		$aObjects[]        = new CommitObject($aParams);

		$aParams['action'] = 'D';
		$aParams['item']   = '/hookframework/tmp//newfolder/testfile.txt';
		$aParams['isdir']  = false;
		$aObjects[]        = new CommitObject($aParams);

		$aParams['action'] = 'D';
		$aParams['item']   = '/hookframework/tmp//newfolder/';
		$aParams['isdir']  = true;
		$aObjects[]        = new CommitObject($aParams);

		$oInfo->setObjects($aObjects);

		$sMail = $this->oMailingListener->processAction($oInfo);

		$sDir = '/hookframework/';

		$sExpected  = 'Zeitpunkt : 2008-12-30 14:56:23' . "\n\n";
		$sExpected .= 'Benutzer  : testuser' . "\n\n";
		$sExpected .= 'Kommentar : * Testkommentar fuer die ';
		$sExpected .= 'Unit Tests.' . "\n\n";

		$sExpected .= str_repeat('=', 80) . "\n";
		$sExpected .= 'Verzeichnis, Dateiinformationen:' . "\n";
		$sExpected .= str_repeat('-', 40) . "\n";
		$sExpected .= 'Hinzugefügt  : 2' . "\n";
		$sExpected .= 'Aktualisiert : 2' . "\n";
		$sExpected .= 'Gelöscht ... : 2' . "\n";
		$sExpected .= "\n";
		$sExpected .= $sDir . 'Core/Commit/CommitBase.php (geändert)' . "\n";
		$sExpected .= $sDir . 'Core/Commit/CommitObject.php (geändert)' . "\n";
		$sExpected .= $sDir . 'doc/hooktemplates/ (neu)' . "\n";
		$sExpected .= $sDir . 'doc/hooktemplates/pre-commit (neu)' . "\n";
		$sExpected .= $sDir . 'tmp//newfolder/testfile.txt (gelöscht)' . "\n";
		$sExpected .= $sDir . 'tmp//newfolder/ (gelöscht)' . "\n";

		$this->assertEquals($sExpected, $sMail);
	} // function


	/**
	 * Testen des Formats der Mail.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMailBodyII()
	{
		// Info- Objekt erzeugen.
		$sUser = 'testuser';
		$sDate = '2008-12-30 14:56:23';
		$sText = '* Testkommentar fuer die Unit Tests.';

		$oInfo = new CommitInfo('666-1', 666, $sUser, $sDate, $sText);

		// Testdaten fuer Dateien.
		$aPath[] = '/hookframework/Core/Commit/CommitBase.php';
		$aPath[] = '/hookframework/Core/Commit/CommitObject.php';

		// Dateiobjekte erzeugen.
		$aParams = array(
					'txn'    => '',
					'rev'    => 666,
					'action' => 'U',
					'item'   => $aPath[0],
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array(),
					'info'   => $oInfo
				   );

		$aObjects   = array();
		$aObjects[] = new CommitObject($aParams);

		$aParams['item'] = $aPath[1];
		$aObjects[]      = new CommitObject($aParams);

		$oInfo->setObjects($aObjects);

		$sMail = $this->oMailingListener->processAction($oInfo);

		$sDir = '/hookframework/';

		$sExpected  = 'Zeitpunkt : 2008-12-30 14:56:23' . "\n\n";
		$sExpected .= 'Benutzer  : testuser' . "\n\n";
		$sExpected .= 'Kommentar : * Testkommentar fuer die ';
		$sExpected .= 'Unit Tests.' . "\n\n";

		$sExpected .= str_repeat('=', 80) . "\n";
		$sExpected .= 'Verzeichnis, Dateiinformationen:' . "\n";
		$sExpected .= str_repeat('-', 40) . "\n";
		$sExpected .= 'Aktualisiert : 2' . "\n";
		$sExpected .= "\n";
		$sExpected .= $sDir . 'Core/Commit/CommitBase.php (geändert)' . "\n";
		$sExpected .= $sDir . 'Core/Commit/CommitObject.php (geändert)' . "\n";

		$this->assertEquals($sExpected, $sMail);
	} // function
} // class
