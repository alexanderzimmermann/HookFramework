<?php
/**
 * Hauptdatei fuer das Hook Framework.
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

use Hook\Core\Repository;
use Hook\Core\Svn;
use Hook\Commit\Data;
use Hook\Commit\Data\Object;
use Hook\Commit\Parser;
use Hook\Listener\AbstractInfo;
use Hook\Listener\AbstractObject;

/**
 * Main class for the Hook Framework.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Hook
{
	/**
	 * Ini file settings.
	 * @var array
	 */
	protected $aCfg;

	/**
	 * Arguments Object.
	 * @var Arguments
	 */
	private $oArguments;

	/**
	 * Commit Data Object.
	 * @var Data
	 */
	private $oCommitData;

	/**
	 * Svn-Object.
	 * @var Svn
	 */
	private $oSvn;

	/**
	 * Repository object.
	 * @var Repository
	 */
	private $oRepository;

	/**
	 * Error-Object.
	 * @var Error
	 */
	protected $oError;

	/**
	 * Log Object.
	 * @var Log
	 */
	protected $oLog;

	/**
	 * List with Listener object for Info and object.
	 * @var array
	 */
	private $aListener;

	/**
	 * Are errors occurred.
	 * @var boolean
	 */
	private $bError;

	/**
	 * List of error messages.
	 * @var array
	 */
	private $aErrors;

	/**
	 * Files that are written for the listener.
	 * @var array
	 */
	private $aWrittenFiles;

	/**
	 * Files that the listener ordered.
	 * @var array
	 */
	private $aListenerFiles;

	/**
	 * Messages.
	 * @var string
	 */
	private $sMessages;

	/**
	 * Constructor.
	 *
	 * There are 4 parameters expected.
	 * <b>Mode</b>
	 * <ul>
	 * <li>Repository (REPOS)</li>
	 * <li>Transaction-Nr. (TXN)</li>
	 * <li>Path to SVN (/usr/bin/svnlook)</li>
	 * <li>Mode: z.B. pre-commit, post-commit</li>
	 * </ul>
	 *
	 * <ul>
	 * <li>post-commit</li>
	 * <li>post-lock</li>
	 * <li>post-revprop-change</li>
	 * <li>post-unlock</li>
	 * <li>pre-commit</li>
	 * <li>pre-lock</li>
	 * <li>pre-revprop-change</li>
	 * <li>pre-unlock</li>
	 * <li>start-commit</li>
	 * </ul>
	 * @param array $aArguments Kommandozeilenargumente.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(array $aArguments)
	{
		$this->oLog = Log::getInstance();
		$this->parseIniFile();

		// Init log object.
		$this->oLog->setLogFile($this->aCfg['logfile']);
		$this->oLog->setLogMode($this->aCfg['logmode']);
		$this->oLog->writeLog(Log::HF_VARDUMP, 'Argumente', $aArguments);

		$this->bStart         = false;
		$this->aErrors        = array();
		$this->aWarnings      = array();
		$this->aWrittenFiles  = array();
		$this->aListenerFiles = array();
		$this->sMessages      = '';
		$this->oArguments     = new Arguments($aArguments);
		$this->oError         = new Error();
	} // function

	/**
	 * Parse INI- file.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function parseIniFile()
	{
		$sPath      = dirname(__FILE__) . '/../';
		$this->aCfg = parse_ini_file($sPath . 'config.ini');
	} // function

	/**
	 * Initialize hook.
	 * @return integer
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function init()
	{
		$this->oLog->writeLog(Log::HF_INFO, 'Init');

		if ($this->oArguments->argumentsOk() === false)
		{
			$this->bError = true;
			$this->oLog->writeLog(Log::HF_INFO, 'Arguments Error');
			$this->showUsage();
			return false;
		} // if

		// Create the repository handler.
		$sReposDir = null;
		if (true === isset($this->aCfg['repositories']))
		{
			$sReposDir = $this->aCfg['repositories'];
		} // if

		$this->oRepository = new Repository($this->oArguments);
		$this->oRepository->setPath($sReposDir);
		$this->oRepository->init();

		// Change log file if a separate exists for the repository.
		if (true === $this->oRepository->hasLogfile())
		{
			$this->oLog->setLogFile($this->oRepository->getLogfile());
		} // if

		// No listener available? Then abort here (performance).
		$this->aListener = $this->oRepository->getListener();
		if (empty($this->aListener) === true)
		{
			$sMessage = 'No listener: Abort';
			$this->oLog->writeLog(Log::HF_DEBUG, $sMessage);

			// Abort but no error for hook when no listener is available.
			return false;
		} // if

		// Create svn object.
		$this->oSvn = new Svn($this->aCfg['binpath']);
		$this->oSvn->init($this->oArguments);

		$this->oLog->writeLog(Log::HF_INFO, 'done');

		return true;
	} // function

	/**
	 * Show usage.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function showUsage()
	{
		include_once __DIR__ . '/Usage.php';

		$sMainType = $this->oArguments->getMainType();
		$sSubType  = $this->oArguments->getSubType();
		$oUsage    = new Usage($sMainType, $sSubType);

		echo $oUsage->getUsage();
	} // function

	/**
	 * Error handling of listener.
	 * @return integer
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function handleErrors()
	{
		if ($this->oError->getError() === true)
		{
			$sErrors = $this->oError->getMessages();
			fwrite(STDERR, $sErrors);

			$this->oLog->writeLog(Log::HF_INFO, 'errors', $sErrors);
			$this->oLog->writeLog(Log::HF_INFO, 'exit 1');
			return 1;
		} // if

		$this->oLog->writeLog(Log::HF_INFO, 'exit 0');

		return 0;
	} // function

	/**
	 * Run the hook.
	 * @return integer
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function run()
	{
		// Initialize, false when errors occurred.
		if ($this->init() === false)
		{
			if ($this->bError === true)
			{
				return 1;
			} // if

			// Empty Listener are an abort, but no error.
			if (empty($this->aListener) === true)
			{
				return 0;
			} // if
		} // if

		$this->oLog->writeLog(Log::HF_INFO, 'run');

		// Parse the commits.
		$this->oLog->writeLog(Log::HF_INFO, 'parse commit');
		$oParser           = new Parser($this->oArguments, $this->oSvn);
		$this->oCommitData = $oParser->getCommitDataObject();

		// Iterate over the listener.
		$this->oLog->writeLog(Log::HF_INFO, 'run listener');
		$this->runListenerInfo();
		$this->runListenerObject();

		// Cleanup files.
		$this->deleteFiles();

		return $this->handleErrors();
	} // function

	/**
	 * Call info listener.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function runListenerInfo()
	{
		if (empty($this->aListener['info']) === true)
		{
			return;
		} // if

		$iMax = count($this->aListener['info']);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$this->aListenerFiles = array();
			$this->processInfoListener($this->aListener['info'][$iFor]);
		} // for
	} // function

	/**
	 * Call Listener for Info.
	 * @param AbstractInfo $oListener Listener.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function processInfoListener(AbstractInfo $oListener)
	{
		// No files, call listener once.
		$sLog  = 'process info listener ';
		$sLog .= $oListener->getListenerName();
		$this->oLog->writeLog(Log::HF_INFO, $sLog);

		$oInfo = $this->oCommitData->getInfo();

		$oListener->processAction($oInfo);

		$this->oError->setListener($oListener->getListenerName());
		$this->oError->processActionInfo($oInfo);
	} // function

	/**
	 * Call Listener.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function runListenerObject()
	{
		if (empty($this->aListener['object']) === true)
		{
			return;
		} // if

		$iMax = count($this->aListener['object']);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$sLog  = 'process object listener ';
			$sLog .= $this->aListener['object'][$iFor]->getListenerName();
			$this->oLog->writeLog(Log::HF_DEBUG, $sLog);

			$this->aListenerFiles = array();
			$this->processObjectListener($this->aListener['object'][$iFor]);
		} // for
	} // function

	/**
	 * Execute Listener.
	 * @param AbstractObject $oListener Listener Object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function processObjectListener(AbstractObject $oListener)
	{
		$aObjects = $this->oCommitData->getObjects($oListener);

		$iMax = count($aObjects);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$this->writeFile($aObjects[$iFor]);

			$oListener->processAction($aObjects[$iFor]);

			$this->oError->setListener($oListener->getListenerName());
			$this->oError->processActionObject($aObjects[$iFor]);
		} // for
	} // function

	/**
	 * Write file to disk.
	 * @param Object $oObject File object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function writeFile(Object $oObject)
	{
		$sFile    = $oObject->getObjectPath();
		$sTmpFile = $oObject->getTmpObjectPath();

		$sLog = 'process file "' . $sFile . '"';
		$this->oLog->writeLog(Log::HF_INFO, $sLog);

		$this->oSvn->getContent($sFile, $sTmpFile);

		$this->addFile($sTmpFile);
	} // function

	/**
	 * Created files store in an array.
	 * @param string $sFile Created file.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function addFile($sFile)
	{
		if (in_array($sFile, $this->aWrittenFiles) === false)
		{
			$this->aWrittenFiles[] = $sFile;
		} // if

		$this->aListenerFiles[] = $sFile;
	} // function

	/**
	 * Cleanup files that are created.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function deleteFiles()
	{
		$iMax = count($this->aWrittenFiles);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			unlink($this->aWrittenFiles[$iFor]);

			$sMessage = 'delete: ' . $this->aWrittenFiles[$iFor];
			$this->oLog->writeLog(Log::HF_DEBUG, $sMessage);
		} // for
	} // function
} // class

