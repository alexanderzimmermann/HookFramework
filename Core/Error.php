<?php
/**
 * Error Objekt fuer die Fehlermeldungen aus den Listern und der Fehlerausgabe.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Error Objekt fuer die Fehlermeldungen aus den Listern und der Fehlerausgabe.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Error
{
	/**
	 * Fehlerzeilen nach Dateien.
	 * @var array
	 */
	private $aLines;

	/**
	 * Fehlerzeilen fuer die Info Elemente.
	 * @var array
	 */
	private $aInfoLines;

	/**
	 * Standard Fehlerzeilen von anderen Fehlern.
	 * @var array
	 */
	private $aCommonLines;

	/**
	 * Schalter ob Fehler vorhanden.
	 * @var boolean
	 */
	private $bError;

	/**
	 * Standard Fehler Schalter.
	 * @var boolean
	 */
	private $bCommonError;

	/**
	 * Aktueller Listener.
	 * @var string
	 */
	private $sListener;

	/**
	 * Constructor.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct()
	{
		$this->aLines = array();
		$this->bError = false;

		$this->aCommonLines = array();
		$this->bCommonError = false;
	} // function

	/**
	 * Setzen des Listenernamens im Array.
	 * @param string $sName Listenername.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setListener($sName)
	{
		$this->sListener = $sName;
	} // function

	/**
	 * Fehler aus den Listener verarbeiten.
	 * @param CommitInfo $oInfo Commit Info Objekt.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processActionInfo(CommitInfo $oInfo)
	{
		$aLines = $oInfo->getErrorLines();

		if (empty($aLines) === false)
		{
			if (isset($this->aInfoLines) === false)
			{
				$this->aInfoLines = array();
			} // if

			$this->aInfoLines[] = $this->sListener;
			$this->aInfoLines[] = str_repeat('=', 80);

			$this->aInfoLines = array_merge($this->aInfoLines, $aLines);

			$this->bError = true;
		} // if
	} // function

	/**
	 * Fehler aus den Listener verarbeiten.
	 * @param CommitObject $oObject Aktuelles File Objekt das verarbeitet wird.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processActionObject(CommitObject $oObject)
	{
		$aLines = $oObject->getErrorLines();

		if (empty($aLines) === false)
		{
			$sFile = $oObject->getObjectPath();
			if (isset($this->aLines[$sFile]) === false)
			{
				$this->aLines[$sFile] = array();
			} // if

			$this->aLines[$sFile][] = $this->sListener;
			$this->aLines[$sFile][] = str_repeat('=', 80);

			$this->aLines[$sFile] = array_merge($this->aLines[$sFile], $aLines);

			$this->bError = true;
		} // if
	} // function

	/**
	 * Fehler hinzufuegen.
	 * @param string $sMessage Text der Fehlermeldung.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addError($sMessage)
	{
		$this->bCommonError   = true;
		$this->aCommonLines[] = $sMessage;
	} // function

	/**
	 * Fehlerzeilen hinzufeugen.
	 * @param array $aLines Fehlerzeilen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addErrorLines(array $aLines)
	{
		$this->bCommonError = true;
		$this->aCommonLines = array_merge($this->aCommonLines, $aLines);
	} // function

	/**
	 * Fehlermeldungen die bis jetzt aufgelaufen sind, danach leeren.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getMessages()
	{
		if ((empty($this->aLines) === true) &&
			(empty($this->aInfoLines) === true))
		{
			return '';
		} // if

		$sMessage = "\n\n" . str_repeat('~', 80) . "\n";

		// Zuerst die Info Listener Zeilen.
		if (empty($this->aInfoLines) === false)
		{
			$sMessage .= implode("\n", $this->aInfoLines);
		} // if

		// Listener fuer die Dateien.
		if (empty($this->aLines) === false)
		{
			$bPrintLine = false;
			foreach ($this->aLines as $sFile => $aFileLines)
			{
				if ($bPrintLine === true)
				{
					$sMessage .= "\n\n";
				} // if

				$sMessage .= $sFile . "\n";
				$sMessage .= str_repeat('-', 80) . "\n";
				$sMessage .= implode("\n", $aFileLines);

				$bPrintLine = true;
			} // foreach
		} // if

		$sMessage .= "\n\n";
		$sMessage .= str_repeat('~', 80) . "\n";

		$this->aLines = array();
		$this->bError = false;

		return $sMessage;
	} // function

	/**
	 * Standardfehlermeldungen zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getCommonMessages()
	{
		$sMessage = implode("\n", $this->aCommonLines);

		$this->aCommonLines = array();
		$this->bCommonError = false;

		return $sMessage;
	} // function

	/**
	 * Sind Meldungen vorhanden.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getError()
	{
		return $this->bError;
	} // function

	/**
	 * Standard Meldungen vorhanen.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getCommonError()
	{
		return $this->bCommonError;
	} // function
} // class
