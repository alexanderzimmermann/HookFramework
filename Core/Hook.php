<?php
/**
 * Hauptdatei fuer das Hook Framework.
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

// Logger einfuegen.
require_once 'Core/Log.php';

// Commit Parser einfuegen.
require_once 'Core/Commit/CommitParser.php';

// Listener Parser einfuegen.
require_once 'Core/Listener/ListenerParser.php';

// Argumente der Kommandozeile pruefen.
require_once 'Core/Arguments.php';

// Argumente der Kommandozeile pruefen.
require_once 'Core/Repository.php';

// Svn Objekt.
require_once 'Core/Svn.php';

// Error Objekt.
require_once 'Core/Error.php';

/**
 * Hauptdatei fuer das Hook Framework.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class HookMain
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
	 * CommitData Object.
	 * @var CommitData
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
	 * Liste mit den Listener Objekten fuer Info und Object.
	 * @var array
	 */
	private $aListener;

	/**
	 * Fehler aufgetreten.
	 * @var boolean
	 */
	private $bError;

	/**
	 * Liste der Fehlermeldungen.
	 * @var array
	 */
	private $aErrors;

	/**
	 * Dateien die bereits geschrieben wurden fuer die Listener.
	 * @var array
	 */
	private $aWrittenFiles;

	/**
	 * Dateien die der Listener angefordert hat.
	 * @var array
	 */
	private $aListenerFiles;

	/**
	 * Meldungszeilen.
	 * @var string
	 */
	private $sMessages;

	/**
	 * Constructor.
	 *
	 * Es werden 4 Parameter erwartet.
	 * <b>Modus</b>
	 * <ul>
	 * <li>Repository (REPOS)</li>
	 * <li>Transaction-Nr. (TXN)</li>
	 * <li>Pfad zum SVN (/usr/bin/svnlook)</li>
	 * <li>Modus: z.B. pre-commit, post-commit</li>
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

		// Log initialisieren, wird auch fuer die anderen Objekte verwendet.
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
	 * Parsen INI- Datei.
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
	 * Anzeigen des korrekten Aufrufs.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function showUsage()
	{
		include_once 'Core/Usage.php';

		$sMainType = $this->oArguments->getMainType();
		$sSubType  = $this->oArguments->getSubType();
		$oUsage    = new Usage($sMainType, $sSubType);

		echo $oUsage->getUsage();
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
			fwrite(STDERR, $sErrors);

			$this->oLog->writeLog(Log::HF_INFO, 'errors', $sErrors);
			$this->oLog->writeLog(Log::HF_INFO, 'exit 1');
			return 1;
		} // if

		$this->oLog->writeLog(Log::HF_INFO, 'exit 0');

		return 0;
	} // function

	/**
	 * Starten des Checks.
	 * @return integer
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function run()
	{
		// Initialisieren, false wenn fehlgeschlagen.
		if ($this->init() === false)
		{
			if ($this->bError === true)
			{
				return 1;
			} // if

			// Leere Listener sind ein Abbruch, aber kein Fehler.
			if (empty($this->aListener) === true)
			{
				return 0;
			} // if
		} // if

		$this->oLog->writeLog(Log::HF_INFO, 'run');

		// Parsen des Commits.
		$this->oLog->writeLog(Log::HF_INFO, 'parse commit');
		$oCommitParser     = new CommitParser($this->oArguments, $this->oSvn);
		$this->oCommitData = $oCommitParser->getCommitDataObject();

		// Listener durchlaufen.
		$this->oLog->writeLog(Log::HF_INFO, 'run listener');
		$this->runListenerInfo();
		$this->runListenerObject();

		// Dateien aufraeumen.
		$this->deleteFiles();

		return $this->handleErrors();
	} // function

	/**
	 * Listener aufrufen.
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
	 * Listener fuer Info ausfuehren.
	 * @param ListenerInfoAbstract $oListener Listener.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function processInfoListener(ListenerInfoAbstract $oListener)
	{
		// Keine Dateien dann den Listener einmal aufrufen.
		$sLog  = 'process info listener ';
		$sLog .= $oListener->getListenerName();
		$this->oLog->writeLog(Log::HF_INFO, $sLog);

		$oCommitInfo = $this->oCommitData->getCommitInfo();

		$oListener->processAction($oCommitInfo);

		$this->oError->setListener($oListener->getListenerName());
		$this->oError->processActionInfo($oCommitInfo);
	} // function

	/**
	 * Listener aufrufen.
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
	 * Listener ausfuehren.
	 * @param ListenerObjectAbstract $oListener Listener Objekt.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function processObjectListener(ListenerObjectAbstract $oListener)
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
	 * Schreiben der Dateien auf die Platte.
	 * @param CommitObject $oObject Objektdatei.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function writeFile(CommitObject $oObject)
	{
		$sFile    = $oObject->getObjectPath();
		$sTmpFile = $oObject->getTmpObjectPath();

		$sLog = 'process file "' . $sFile . '"';
		$this->oLog->writeLog(Log::HF_INFO, $sLog);

		$sContent = $this->oSvn->getContent($sFile, $sTmpFile);

		$this->addFile($sTmpFile);
	} // function

	/**
	 * Erzeugte Dateien in einem Array verwalten.
	 * @param string $sFile Geschriebene Datei.
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
	 * Dateien aufraeumen die aus dem Commit erstellt wurden.
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

