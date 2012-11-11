<?php
/**
 * Message Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Example\Pre;

use Hook\Commit\Data\Info;
use Hook\Listener\AbstractInfo;

/**
 * Message Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Message extends AbstractInfo
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Commit Message';

	/**
	 * Register the action.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
		return 'commit';
	} // function

	/**
	 * Execute the action.
	 * @param Info $oInfo Info des Commits.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(Info $oInfo)
	{
		// Check message has at least 10 Chars.
		$sMessage = $oInfo->getMessage();

		$this->checkMessage($oInfo, $sMessage);
	} // function

	/**
	 * Pruefen ob der Text fuer die Meldung den Vorgaben entspricht.
	 * @param Info $oInfo    Commit Daten Objekt.
	 * @param string     $sMessage Text zu Commit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkMessage(Info $oInfo, $sMessage)
	{
		$sMessage = trim($sMessage);

		if ($sMessage === '')
		{
			$sErrorMessage  = 'Bitte einen Kommentar angeben und den';
			$sErrorMessage .= ' Kommentar bitte wie folgt einleiten:' . "\n";
			$sErrorMessage .= '+ Wenn etwas neues hinzugefuegt wird.' . "\n";
			$sErrorMessage .= '- Wenn etwas entfernt wird.' . "\n";
			$sErrorMessage .= '* Bei Aenduerungen der Datei.' . "\n";

			$oInfo->addError($sErrorMessage);

			return;
		} // if

		// Wird Kommentar mit +, - oder * eingeleitet?
		if (preg_match('/^[\*+\-]+ /i', $sMessage) === 0)
		{
			$sErrorMessage  = 'Kommentar bitte wie folgt einleiten:' . "\n";
			$sErrorMessage .= '+ Wenn etwas neues hinzugefuegt wird.' . "\n";
			$sErrorMessage .= '- Wenn etwas entfernt wird.' . "\n";
			$sErrorMessage .= '* Bei Aenduerungen der Datei.' . "\n";

			$oInfo->addError($sErrorMessage);
		} // if

		$sMessage = preg_replace('/^[\*+\-]+ /i', '', $sMessage);

		// Ist der Kommentar wenigstens 10 Zeichen lang?
		if (strlen($sMessage) < 10)
		{
			$sErrorMessage = 'Der Kommentar sollte aussagekraeftig sein!';
			$oInfo->addError($sErrorMessage);
		} // if
	} // function
} // class
