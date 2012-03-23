<?php
/**
 * Mailing Listener.
 * @category   Listener
 * @package    Post
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Mailing Listener.
 * @category   Listener
 * @package    Post
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Mailing extends ListenerInfoAbstract
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Post Commit Mailing';

	/**
	 * Commit Info Objekt.
	 * @var CommitInfo
	 */
	private $oInfo;

	/**
	 * Registrieren auf die Aktion.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
		return 'commit';
	} // function

	/**
	 * Ausfuehren der Aktion.
	 * @param CommitInfo $oInfo Info des Commits.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitInfo $oInfo)
	{
		$this->oInfo = $oInfo;

		$sMailBody  = $this->getCommitInfoMailHead();
		$sMailBody .= $this->getCommitObjectsMailBody();

		// Mailen.
		$sHeader  = 'From: webmaster@example.com' . "\r\n";
		$sHeader .= 'Reply-To: webmaster@example.com' . "\r\n";
		$sHeader .= 'Content-Type: text/plain; char-set=UTF-8' . "\r\n";
		$sHeader .= 'X-Mailer: PHP/' . phpversion();

		mail('alex@aimmermann.com', 'SVN Commit', $sMailBody, $sHeader);

		return $sMailBody;
	} // function

	/**
	 * Aufbereiten Commit Info.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getCommitInfoMailHead()
	{
		$sHead  = 'Zeitpunkt : ' . $this->oInfo->getDateTime() . "\n\n";
		$sHead .= 'Benutzer  : ' . $this->oInfo->getUser() . "\n";
		$sHead .= "\n";
		$sHead .= 'Kommentar : ' . $this->oInfo->getMessage() . "\n\n";

		$sHead .= str_repeat('=', 80) . "\n";

		return $sHead;
	} // function

	/**
	 * Aufbereiten der Dateiinformationen fuer den Mailinhalt.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getCommitObjectsMailBody()
	{
		$aAdded   = array();
		$aUpdated = array();
		$aDeleted = array();

		$aObjects = $this->oInfo->getObjects();
		$iMax     = count($aObjects);

		$sFileList = '';
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$sFileList .= $aObjects[$iFor]->getObjectPath();

			if ($aObjects[$iFor]->getAction() === 'A')
			{
				$aAdded[]   = $aObjects[$iFor]->getObjectPath();
				$sFileList .= ' (neu)';
			} // if

			if ($aObjects[$iFor]->getAction() === 'U')
			{
				$aUpdated[] = $aObjects[$iFor]->getObjectPath();
				$sFileList .= ' (geändert)';
			} // if

			if ($aObjects[$iFor]->getAction() === 'D')
			{
				$aDeleted[] = $aObjects[$iFor]->getObjectPath();
				$sFileList .= ' (gelöscht)';
			} // if

			$sFileList .= "\n";
		} // for

		$sMailBody  = 'Verzeichnis, Dateiinformationen:' . "\n";
		$sMailBody .= str_repeat('-', 40) . "\n";

		if (empty($aAdded) === false)
		{
			$sMailBody .= 'Hinzugefügt  : ' . count($aAdded) . "\n";

			$sFileListAdded = implode("\n", $aAdded);
		} // if

		if (empty($aUpdated) === false)
		{
			$sMailBody .= 'Aktualisiert : ' . count($aUpdated) . "\n";

			$sFileListUpdated = implode("\n", $aUpdated);
		} // if

		if (empty($aDeleted) === false)
		{
			$sMailBody .= 'Gelöscht ... : ' . count($aDeleted) . "\n";

			$sFileListDeleted = implode("\n", $aDeleted);
		} // if

		$sMailBody .= "\n" . $sFileList;

		return $sMailBody;
	} // function
} // class
