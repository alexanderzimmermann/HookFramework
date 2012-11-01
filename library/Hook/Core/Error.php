<?php
/**
 * Error Object for the error messages from the listeners and the output.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Core;

use Hook\Commit\CommitInfo;
use Hook\Commit\CommitObject;

/**
 * Error Object for the error messages from the listeners and the output.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Error
{
	/**
	 * Error lines for files.
	 * @var array
	 */
	private $aLines;

	/**
	 * Error lines for the Info elements.
	 * @var array
	 */
	private $aInfoLines;

	/**
	 * Standard error lines of other errors.
	 * @var array
	 */
	private $aCommonLines;

	/**
	 * Switch if errors messages are available.
	 * @var boolean
	 */
	private $bError;

	/**
	 * Standard error switch.
	 * @var boolean
	 */
	private $bCommonError;

	/**
	 * Actual Listener.
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
	 * Set the Listener names in Array.
	 * @param string $sName Listenername.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setListener($sName)
	{
		$this->sListener = $sName;
	} // function

	/**
	 * Format the info listener error messages.
	 * @param CommitInfo $oInfo Commit Info Object.
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
	 * Format the object listener error messages.
	 * @param CommitObject $oObject Actual File Object that is processed.
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
	 * Add an error message.
	 * @param string $sMessage Text for error message.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addError($sMessage)
	{
		$this->bCommonError   = true;
		$this->aCommonLines[] = $sMessage;
	} // function

	/**
	 * Add error lines.
	 * @param array $aLines Error lines.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addErrorLines(array $aLines)
	{
		$this->bCommonError = true;
		$this->aCommonLines = array_merge($this->aCommonLines, $aLines);
	} // function

	/**
	 * Return messages that occurred so far, after that clear messages.
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

		// First the Info listener lines.
		if (empty($this->aInfoLines) === false)
		{
			$sMessage .= implode("\n", $this->aInfoLines);
		} // if

		// Listener lines for the files.
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
	 * Return standard error messages.
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
	 * Are there any error messages.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getError()
	{
		return $this->bError;
	} // function

	/**
	 * Standard messages available.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getCommonError()
	{
		return $this->bCommonError;
	} // function
} // class
