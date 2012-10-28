<?php
/**
 * Little log object.
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

namespace Core;

/**
 * Little log object.
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
class Log
{
	/**
	 * Just infos, normal protocol.
	 */
	const HF_INFO = 1;

	/**
	 * For a simple debug mode..
	 */
	const HF_DEBUG = 2;

	/**
	 * Need some dumps for debugging.
	 */
	const HF_VARDUMP = 3;

	/**
	 * Log object.
	 * @var Log
	 */
	static private $oLog = null;

	/**
	 * Log Mode.
	 * @var integer
	 */
	private $iLogMode;

	/**
	 * Resource to log file.
	 * @var resource
	 */
	private $rFile;

	/**
	 * Construct not allowed.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function __construct()
	{
	} // function

	/**
	 * Clone not allowed.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function __clone()
	{
	} // function

	/**
	 * Get the log instance.
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
	 * Set the logfile name.
	 * @param string $sFile Log file.
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
	 * Sets the log mode.
	 * @param integer $iLogMode Log mode.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setLogMode($iLogMode = self::HF_INFO)
	{
		$this->iLogMode = $iLogMode;
	} // function

	/**
	 * Write log.
	 * @param integer $iLogMode Log mode.
	 * @param string  $sHeadMsg Headline for var.
	 * @param mixed   $mVar     Variable for Debug.
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
