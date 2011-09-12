<?php
/**
 * Repository Class that handles the availability of Listener and Log.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.1
 */

// Listener Parser einfuegen.
require_once 'Core/Listener/ListenerParser.php';

/**
 * Handles listener and logpath for each subversion repository.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
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
	 * @var sring
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
	 * List of the catual listener for this repository and hook action.
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
	 * @author Alexander Zimmermann <alexander.zimmermann@twt.de>
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

		// Check if there is a respository path.
		if (false === is_dir($sDirectory . ucfirst($this->sType)))
		{
			// Use the Listener that come with the hookframework.
			$sDirectory = dirname(__FILE__) . '/../Listener/';
		} // if

		// Parse listener in directory.
		$oListenerParser = new ListenerParser($this->oArguments);
		$oListenerParser->setPath($sDirectory);
		$oListenerParser->init();
		$this->aListener = $oListenerParser->getListener();
		unset($oListenerParser);

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
	 * @author Alexander Zimmermann <alexander.zimmermann@twt.de>
	 */
	public function hasLogfile()
	{
		return $this->bUseLog;
	} // function

	/**
	 * Return path and logfile.
	 * @return string
	 * @author Alexander Zimmermann <alexander.zimmermann@twt.de>
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
