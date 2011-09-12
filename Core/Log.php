<?php
/**
 * Kleiner Logger.
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
 * Kleiner Logger.
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
class Log
{
	/**
	 * Nur Infos, normales Protkoll.
	 */
	const HF_INFO = 1;

	/**
	 * Fuer einen einfachen Debug Mode.
	 */
	const HF_DEBUG = 2;

	/**
	 * Erweitert um Variablen Dumps.
	 */
	const HF_VARDUMP = 3;

	/**
	 * Logger Instanz.
	 * @var Log
	 */
	static private $oLog = null;

	/**
	 * Log Modus.
	 * @var integer
	 */
	private $iLogMode;

	/**
	 * Resource auf Logdatei.
	 * @var resource
	 */
	private $rFile;

	/**
	 * Konstruktor / Nicht erlauben.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function __construct()
	{
	} // function

	/**
	 * Klonen nicht erlauben.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function __clone()
	{
	} // function

	/**
	 * Instanz des Loggers holen.
	 * @return Log
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public static function getInstance()
	{
		if (self::$oLog === null)
		{
			self::$oLog = new self;
		} // if

		return self::$oLog;
	} // function

	/**
	 * Logdatei setzen.
	 * @param string $sFile Log Datei.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setLogFile($sFile)
	{
		$this->rFile = fopen($sFile, 'a+');

		fwrite($this->rFile, str_repeat('=', 80) . "\n");
		fwrite($this->rFile, str_repeat(' ', 20) . date('Y-m-d H:i:s') . "\n");
		fwrite($this->rFile, str_repeat('=', 80) . "\n");
	} // function

	/**
	 * Log Modus setzen.
	 * @param integer $iLogMode Log Modus.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setLogMode($iLogMode = 1)
	{
		$this->iLogMode = $iLogMode;
	} // function

	/**
	 * Loggen.
	 * @param integer $iLogMode Log Modus.
	 * @param string  $sHeadMsg Ueberschrift fuer Var.
	 * @param mixed   $mVar     Variable fuer Debug.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function writeLog($iLogMode, $sHeadMsg = '', $mVar = null)
	{
		if ($this->iLogMode < $iLogMode)
		{
			return;
		} // if

		$sLogLine = $sHeadMsg . "\n";

		$sVarDump = '';
		if ($mVar !== null)
		{
			$sVarDump  = var_export($mVar, true);
			$sLogLine .= $sVarDump . "\n";
		} // if

		$sLogLine .= str_repeat('-', 15) . "\n";

		fwrite($this->rFile, $sLogLine);
	} // function
} // class
