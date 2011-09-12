<?php
/**
 * Hilfsklasse fuer die Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

// Hook Objekt.
require_once 'Core/Hook.php';

/**
 * Hilfsklasse fuer die Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class HookHelperMain extends HookMain
{
	/**
	 * Parsen INI- Datei.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function parseIniFile()
	{
		$sPath = dirname(__FILE__) . '/../';
		$this->oLog->writeLog(Log::HF_DEBUG, 'Using tests/config.ini');
		$this->aCfg = parse_ini_file($sPath . 'config.ini');
	} // function

	/**
	 * Fehlerbehandlung der Listener.
	 * @return integer
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function handleErrors()
	{
		if ($this->oError->getError() === true)
		{
			$sErrors = $this->oError->getMessages();

			$this->oLog->writeLog(Log::HF_INFO, 'errors', $sErrors);
			$this->oLog->writeLog(Log::HF_INFO, 'exit 1');
			return 1;
		} // if

		$this->oLog->writeLog(Log::HF_INFO, 'exit 0');

		return 0;
	} // function
} // class
