<?php
/**
 * Repository Class that handles the availability of Listener and Log.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.1
 */

namespace Hook\Core;

use Hook\Listener\Loader;
use Hook\Core\Arguments;

/**
 * Handles listener and log path for each subversion repository.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.1
 */
class Repository
{
	/**
	 * Arguments object.
	 * @var Arguments
	 */
	private $oArguments;

	/**
	 * Path to the repositories listener and logs.
	 * @var string
	 */
	private $sPath;

	/**
	 * Name of repository.
	 * @var string
	 */
	private $sRepos = null;

	/**
	 * Type of actual commit.
	 * @var string
	 */
	private $sType;

	/**
	 * Is a log dir with a writable log file available.
	 * @var boolean
	 */
	private $bUseLog;

	/**
	 * Path and name of the repository logfile.
	 * @var string
	 */
	private $sLogfile;

	/**
	 * List of the actual listener for this repository and hook action.
	 * @var array
	 */
	private $aListener = array();

	/**
	 * Constructor.
	 * @param Arguments $oArguments Repository name.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(Arguments $oArguments)
	{
		$this->oArguments = $oArguments;
		$this->sType      = $oArguments->getMainType();
	} // function

	/**
	 * Set path where the listener and logs are stored.
	 * @param string $sPath Path where all listener and logs are stored.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setPath($sPath)
	{
		$this->sPath = $sPath;
	} // function

	/**
	 * Init.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function init()
	{
		$sDirectory = $this->sPath . $this->oArguments->getRepositoryName() . '/';

		// Check if there is a repository path.
		if (false === is_dir($sDirectory))
		{
			// Use the Listener that come with the hookframework.
			$sDirectory = $this->sPath . 'Example/';
		} // if

		// Parse listener in directory.
		$oLoader = new Loader($this->oArguments);
		$oLoader->setPath($sDirectory);
		$oLoader->init();
		$this->aListener = $oLoader->getListener();
		unset($oLoader);

		// Check if a common.log file is available.
		$this->bUseLog = false;
		$sFile         = $sDirectory . 'logs/common.log';
		if ((true === is_file($sFile)) &&
			(true === is_writable($sFile)))
		{
			$this->bUseLog  = true;
			$this->sLogfile = $sFile;
		} // if
	} // function

	/**
	 * Is a logfile in for the repository provided.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function hasLogfile()
	{
		return $this->bUseLog;
	} // function

	/**
	 * Return path and logfile.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getLogfile()
	{
		return $this->sLogfile;
	} // function

	/**
	 * Return listener objects.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getListener()
	{
		return $this->aListener;
	} // function
} // class
