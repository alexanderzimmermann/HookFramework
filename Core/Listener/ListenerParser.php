<?php
/**
 * Parsen der verschiedenen Typlistener.
 * @category   Core
 * @package    Listener
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

// Interface einfuegen damit die Listener das nicht alle brauchen.
require_once 'Core/Listener/ListenerInfo.php';

// Interface einfuegen damit die Listener das nicht alle brauchen.
require_once 'Core/Listener/ListenerObject.php';

// Eigentlich sollte der Kommentar mit /** */ sein wegen documentor.
// Abstrakte Klasse einfuegen damit die Listener das nicht alle brauchen.
require_once 'Core/Listener/ListenerInfoAbstract.php';

// Eigentlich sollte der Kommentar mit /** */ sein wegen documentor.
// Abstrakte Klasse einfuegen damit die Listener das nicht alle brauchen.
require_once 'Core/Listener/ListenerObjectAbstract.php';

// Logger einfuegen.
require_once 'Core/Log.php';

/**
 * Parsen der verschiedenen Typlistener.
 *
 * Es gibt 3 Arten von Transaktionen. Eine fuer den Start, eine nach dem die
 * Transaktion gestartet wurde aber noch commited (pre). Und eine nachdem die
 * Transaktion und Verarbeitung abgeschlossen wurde (post).
 * <ul>
 * <li>Start</li>
 * <li>Pre</li>
 * <li>Post</li>
 * </ul>
 * @category   Core
 * @package    Listener
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class ListenerParser
{
	/**
	 * Main Hook.
	 * @var Arguments
	 */
	private $oArguments;

	/**
	 * Path to the listener.
	 * @var string
	 */
	private $sPath;

	/**
	 * Liste der Listener Dateien.
	 * @var array
	 */
	private $aListenerFiles;

	/**
	 * Liste der Listener Objekte fuer Dateiobjekte.
	 * @var array
	 */
	private $aListenerObject;

	/**
	 * Liste der Listener Objekte fuer Info.
	 * @var array
	 */
	private $aListenerInfo;

	/**
	 * Fehler warum Listener nicht korrekt war.
	 * @var string
	 */
	private $sError;

	/**
	 * Constructor.
	 * @param Arguments $oArguments Argumentenobjekt.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(Arguments $oArguments)
	{
		$this->oArguments = $oArguments;
		$this->aListener  = array();

		$this->aListenerInfo   = array();
		$this->aListenerObject = array();
	} // function

	/**
	 * Set path where the listener are stored.
	 * @param string $sPath Path where all listener are stored.
	 * @return void
	 * @author Alexander Zimmermann <alexander.zimmermann@twt.de>
	 */
	public function setPath($sPath)
	{
		$this->sPath = $sPath;
	} // function

	/**
	 * Init the Listener Parser.
	 * @return void
	 * @author Alexander Zimmermann <alexander.zimmermann@twt.de>
	 */
	public function init()
	{
		$this->readDirectory($this->oArguments->getMainType());
		$this->checkListener();
		$this->registerListenerInfo();
		$this->registerListenerObject();
        log::getInstance()->writeLog(Log::HF_INFO, $this->sError);
	} // function

	/**
	 * Return listener objects.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getListener()
	{
		$aListener = array();

		if (empty($this->aListenerInfo) === false)
		{
			$aListener['info'] = $this->aListenerInfo;
		} // if

		if (empty($this->aListenerObject) === false)
		{
			$aListener['object'] = $this->aListenerObject;
		} // if

		return $aListener;
	} // function

	/**
	 * Read the files for the actual maion hook action in directory.
	 * @param string $sType Typ der aktuellen Transaktion.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function readDirectory($sType)
	{
        $oLog = log::getInstance();
        $sType     = ucfirst($sType);

        $oIter = new \DirectoryIterator($this->sPath.$sType);
        $aListener = array();

        foreach ($oIter as $oFile)
        {
            /* @var $oFile \SplFileInfo */
            if ($oFile->getExtension() == 'php')
            {
                $aListener[] = $oFile->getPathname();
            }
        }

        $this->aListenerFiles = $aListener;
	} // function

	/**
	 * Register Methoden fuer die Info Listener aufrufen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function registerListenerInfo()
	{
		$iMax = count($this->aListenerInfo);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$bOk = $this->registerListenersInfo($this->aListenerInfo[$iFor]);

			if ($bOk === false)
			{
				unset($this->aListenerInfo[$iFor]);
			} // if
		} // for

		$this->aListenerInfo = array_values($this->aListenerInfo);
	} // function

	/**
	 * Register Methoden fuer die Info Listener aufrufen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function registerListenerObject()
	{
		$iMax = count($this->aListenerObject);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$oListenerObject = $this->aListenerObject[$iFor];

			$bOk = $this->registerListenersObject($oListenerObject);

			if ($bOk === false)
			{
				unset($this->aListenerObject[$iFor]);
			} // if
		} // for

		$this->aListenerObject = array_values($this->aListenerObject);
	} // function

	/**
	 * Register Werte fuer Info Listener abrufen und pruefen.
	 * @param ListenerInfoAbstract $oListener Name des Listener Objekts.
	 * @return Listener
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function registerListenersInfo(ListenerInfoAbstract $oListener)
	{
		$sRegister = $oListener->register();
		$sListener = $oListener->getListenerName();

		// Richtige Typen?
		if (is_string($sRegister) === false)
		{
			$this->sError .= $sListener . ' Register not a String for InfoType';
			return false;
		} // if

		// Typen leer?
		if ($sRegister === '')
		{
			$this->sError .= $sListener . ' Error Register String Empty';
			return false;
		} // if

		// Richtige Werte?
		$aSvnActions = $this->oArguments->getSubActions();

		if (in_array($sRegister, $aSvnActions) === false)
		{
			$this->sError .= $sListener . ' Register Action ';
			$this->sError .= $sRegister . ' not available!';
			return false;
		} // if

		return true;
	} // function

	/**
	 * Register Werte fuer Objekt Listener abrufen und pruefen.
	 * @param ListenerObjectAbstract $oListener Name des Listener Objekts.
	 * @return Listener
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function registerListenersObject(ListenerObjectAbstract $oListener)
	{
		$aRegister = $oListener->register();
		$sListener = $oListener->getListenerName();

		// Alle Werte vorhanden?
		if ((isset($aRegister['action']) === false) ||
		(isset($aRegister['extensions']) === false) ||
		(isset($aRegister['fileaction']) === false))
		{
			$this->sError .= $sListener . ' Error Register Key Elements';
			return false;
		} // if

		// Richtige Typen?
		if ((is_string($aRegister['action']) === false) ||
		(is_array($aRegister['extensions']) === false) ||
		(is_array($aRegister['fileaction']) === false))
		{
			$this->sError .= $sListener . ' Error Register Array Types';
			return false;
		} // if

		// Typen leer?
		if (($aRegister['action'] === '') &&
			(empty($aRegister['extension']) === true) &&
			(empty($aRegister['fileaction']) === true))
		{
				$this->sError .= $sListener . ' Error Register Array Empty';
				return false;
		} // if

		// Richtige Werte?
		$sAction     = $aRegister['action'];
		$aSvnActions = $this->oArguments->getSubActions();

		if (in_array($sAction, $aSvnActions) === false)
		{
			$this->sError .= $sListener . ' Register Action ';
			$this->sError .= $sAction . ' not available!';
			return false;
		} // if

		return true;
	} // function

	/**
	 * Pruefen ob die Listener verwendet werden koennen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkListener()
	{
		$aListener = array();

		$iMax = count($this->aListenerFiles);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			if (file_exists($this->aListenerFiles[$iFor]) === true)
			{
				// Einfuegen der Listenerdatei.
				include_once $this->aListenerFiles[$iFor];

				// Listenername aus den Dateinamen extrahieren.
				$sListener = basename($this->aListenerFiles[$iFor]);
				$sListener = str_replace('.php', '', $sListener);

				// Listener erzeugen und Kategorisieren.
				try
				{
					if ($this->checkListenerObject($sListener) === false)
					{
						unset($this->aListenerFiles[$iFor]);
					}
					else
					{
						$aListener[] = $sListener;
					} // if
				}
				catch (Exception $oException)
				{
					$this->sError .= $oException->getMessage() . PHP_EOL . $oException->getTraceAsString() . PHP_EOL;
				} // try
			} // if
		} // for

		// Die noch verbliebenen korrekten Listener setzen.
		$this->aListenerFiles = $aListener;
        Log::getInstance()->writeLog(Log::HF_VARDUMP, 'Accepted Listeners', $this->aListenerFiles);
	} // function

	/**
	 * Pruefen des eingefuegten Listeners.
	 * @param string $sListener Name des Listenere-Objekts.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkListenerObject($sListener)
	{
		// Existiert die Klasse?
		if (class_exists($sListener) === false)
		{
			$this->sError .= $sListener . ' class and filename dont match!' . "\n";
			return false;
		} // if

		// Implmentiert der Listener das richtige Interface und Abstrakt Klasse?
		$aImplements = class_implements($sListener);
		$aParents    = class_parents($sListener);

		if ((isset($aImplements['ListenerInfo']) === true) &&
			(isset($aParents['ListenerInfoAbstract']) === true))
		{
			// TODO: In einem try catch Block abfangen bei Fehlern.
			$this->aListenerInfo[] = new $sListener;
			return true;
		} // if

		if ((isset($aImplements['ListenerObject']) === true) &&
			(isset($aParents['ListenerObjectAbstract']) === true))
		{
			// TODO: In einem try catch Block abfangen bei Fehlern.
			$this->aListenerObject[] = new $sListener;
			return true;
		} // if

		$sError  = $sListener . ' does not implement or extend correct ';
		$sError .= 'interface or abstract class.!' . "\n";

		$this->sError .= $sError;

		return false;
	} // function
} // class
