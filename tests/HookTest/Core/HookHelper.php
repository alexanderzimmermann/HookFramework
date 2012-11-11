<?php
/**
 * Help class for the tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Core;

use Hook\Core\Hook;
use Hook\Core\Log;

/**
 * Help class for the tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class HookHelper extends Hook
{
	/**
	 * Parse INI- file.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function parseIniFile()
	{
		$sPath = __DIR__ . '/../../';
		$sFile = 'config.ini';

		if (false === file_exists($sPath . $sFile))
		{
			$sFile = 'config-dist.ini';
		} // if

		$this->oLog->writeLog(Log::HF_DEBUG, 'Using tests/' . $sFile);
		$this->aCfg = parse_ini_file($sPath . $sFile);

		// Overwrite configuration file.
		$this->aCfg['binpath']      = __DIR__ . '/../_files/bin/';
		$this->aCfg['repositories'] = __DIR__ . '/../../../Repositories/';
		$this->aCfg['logfile']      = __DIR__ . '/../../common-test.log';
	} // function

	/**
	 * Error handling of the listener.
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
