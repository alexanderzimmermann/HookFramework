<?php
/**
 * Message Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Message Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class MessageStrict extends ListenerInfoAbstract
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Commit Bemerkung Strict';

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
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitInfo $oInfo)
	{
		// Check message has at least 10 Chars.
		$sMessage = $oInfo->getMessage();

		$this->checkMessage($oInfo, $sMessage);
	} // function

	/**
	 * Pruefen ob der Text fuer die Meldung den Vorgaben entspricht.
	 * @param CommitInfo $oInfo    Commit Daten Objekt.
	 * @param string     $sMessage Text zu Commit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkMessage(CommitInfo $oInfo, $sMessage)
	{
		$sMessage = trim($sMessage);

		if ($sMessage === '')
		{
			$sErrorMessage  = 'Bitte einen Kommentar angeben und den ';
			$sErrorMessage .= 'Kommentar bitte wie folgt einleiten:' . "\n";
			$sErrorMessage .= '+ Wenn etwas neues hinzugefuegt wird.' . "\n";
			$sErrorMessage .= '- Wenn etwas entfernt wird.' . "\n";
			$sErrorMessage .= '* Bei Aenduerungen der Datei.' . "\n";

			$oInfo->addError($sErrorMessage);

			return;
		} // if

		// Wird Kommentar mit +, - oder * eingeleitet?
		if (preg_match('/[\*+\-] /i', $sMessage) === 0)
		{
			$sErrorMessage  = 'Kommentar bitte wie folgt einleiten:' . "\n";
			$sErrorMessage .= '+ Wenn etwas neues hinzugefuegt wird.' . "\n";
			$sErrorMessage .= '- Wenn etwas entfernt wird.' . "\n";
			$sErrorMessage .= '* Bei Aenduerungen der Datei.' . "\n";

			$oInfo->addError($sErrorMessage);
		} // if

		$sMessage = preg_replace('[\*+\-] ', '', $sMessage);

		// Ist der Kommentar wenigstens 10 Zeichen lang?
		if (strlen($sMessage) < 10)
		{
			$sErrorMessage = 'Der Kommentar sollte aussagekraeftig sein!';
			$oInfo->addError($sErrorMessage);
		} // if

		// Vollstaendige Saetze.
		$aMessage = explode(' ', $sMessage);
		if (count($aMessage) < 3)
		{
			$sErrorMessage  = 'Der Kommentar sollte bitte vollstaendige Saetze';
			$sErrorMessage .= ' enthalten.';
			$sErrorMessage .= "\n" . 'Subjekt, Praedikat, Objekt, Punkt!';
			$oInfo->addError($sErrorMessage);
		} // if

		// Wortlaenge im Durchschnitt weniger als 3 Zeichen?
		$iMax = count($aMessage);
		$iLen = 0;
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$iLen += strlen($aMessage[$iFor]);
		} // for

		$iSqr = round(($iLen / $iMax), 0);

		if ($iSqr <= 3)
		{
			$sErrorMessage  = 'Der Kommentar sollte bitte vollstaendige Saetze';
			$sErrorMessage .= ' und gute Wortformulierungen enthalten.' . "\n";
			$sErrorMessage .= 'Subjekt, Praedikat, Objekt, Punkt!';
			$oInfo->addError($sErrorMessage);
		} // if

		return true;
	} // function
} // class
