<?php
/**
 * Parsing the different listener types.
 * @category   Core
 * @package    Listener
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Listener;

use \DirectoryIterator;
use Hook\Core\Arguments;
use Hook\Core\Log;

/**
 * Parsing the different listener types.
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
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
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
	 * List of listener files from directory.
	 * @var array
	 */
	private $aListenerFiles;

	/**
	 * Namespace of listener.
	 * @var array
	 */
	private $aListenerNamespace = array();

	/**
	 * List of listener objects for file objects.
	 * @var array
	 */
	private $aListenerObject;

	/**
	 * List of listener object for info objects.
	 * @var array
	 */
	private $aListenerInfo;

	/**
	 * Error why listener failed.
	 * @var string
	 */
	private $sError;

	/**
	 * Constructor.
	 * @param Arguments $oArguments Arguments object.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(Arguments $oArguments)
	{
		$this->oArguments = $oArguments;
		$this->aListener  = array();

		$this->aListenerFiles  = array();
		$this->aListenerInfo   = array();
		$this->aListenerObject = array();
	} // function

	/**
	 * Set path where the listener are stored.
	 * @param string $sPath Path where all listeners are stored.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setPath($sPath)
	{
		$this->sPath = $sPath;
	} // function

	/**
	 * Init the Listener Parser.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function init()
	{
		$this->readDirectory($this->oArguments->getMainType());
		$this->checkListener();
		$this->registerListenerInfo();
		$this->registerListenerObject();
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
	 * Read the files for the actual main hook action in directory.
	 * @param string $sType Type of actual transaction.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function readDirectory($sType)
	{
		$sType = ucfirst($sType);

		// If directory does not exists, return.
		if (false === is_dir($this->sPath . $sType))
		{
			return;
		} // if

		$oIterator = new \DirectoryIterator($this->sPath . $sType);
		$aListener = array();

		foreach ($oIterator as $oFile)
		{
			if (true === $oFile->isFile())
			{
				if (false !== strpos($oFile->getFilename(), 'php'))
				// if ('php' === $oFile->getExtension())
				{
					$aListener[] = $oFile->getPathname();
				} // if
			} // if
		} // foreach

		$this->aListenerFiles = $aListener;
	} // function

	/**
	 * Register Call methods of info listener.
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
	 * Register Call methods of object listener.
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
	 * Register values for info listener and check it.
	 * @param InfoAbstract $oListener Name des Listener Objekts.
	 * @return Listener
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function registerListenersInfo(InfoAbstract $oListener)
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
	 * Register values for object listener and check it.
	 * @param ObjectAbstract $oListener Name des Listener Objekts.
	 * @return Listener
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function registerListenersObject(ObjectAbstract $oListener)
	{
		$aRegister = $oListener->register();
		$sListener = $oListener->getListenerName();

		// All needed values available.
		if ((isset($aRegister['action']) === false) ||
			(isset($aRegister['extensions']) === false) ||
			(isset($aRegister['fileaction']) === false))
		{
			$this->sError .= $sListener . ' Error Register Key Elements';
			return false;
		} // if

		// Correct type?
		if ((is_string($aRegister['action']) === false) ||
			(is_array($aRegister['extensions']) === false) ||
			(is_array($aRegister['fileaction']) === false))
		{
			$this->sError .= $sListener . ' Error Register Array Types';
			return false;
		} // if

		// Type empty?
		if (($aRegister['action'] === '') &&
			(empty($aRegister['extension']) === true) &&
			(empty($aRegister['fileaction']) === true))
		{
				$this->sError .= $sListener . ' Error Register Array Empty';
				return false;
		} // if

		// Valid values?
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
	 * Check if the listener can be used.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkListener()
	{
		$aListener = array();

		$iMax = count($this->aListenerFiles);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			if (true === file_exists($this->aListenerFiles[$iFor]))
			{
				// Include the listener file.
				include_once $this->aListenerFiles[$iFor];

				// Extract listener name from filename.
				$sListener = basename($this->aListenerFiles[$iFor]);
				$sListener = str_replace('.php', '', $sListener);

				// Check the listener is available after including. If the file contains other code
				// like Helper for a listener, we don't want to use it..
				// Create the listener and put in category (info, object) list.
				try
				{
					if (false === $this->checkListenerObject($sListener))
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

		// Sets the listener.
		$this->aListenerFiles = $aListener;

		Log::getInstance()->writeLog(Log::HF_VARDUMP, 'Accepted Listeners', $this->aListenerFiles);
	} // function

	/**
	 * Check the included listener implements the required stuff.
	 * @param string $sListener Name des Listenere-Objekts.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkListenerObject($sListener)
	{
		// Class exists?
		$sClass = $this->oArguments->getRepositoryName() . '\\'
				. ucfirst($this->oArguments->getMainType()) . '\\' . $sListener;

		if (false === class_exists($sClass, false))
		{
			$sMsg          = $sClass . ' class and filename doesn\'t match!';
			$this->sError .= $sMsg . "\n";

			Log::getInstance()->writeLog(Log::HF_DEBUG, $sMsg);

			return false;
		} // if

		// Check for correct interface and abstract class.
		$aImplements = class_implements($sClass);
		$aParents    = class_parents($sClass);

		if ((isset($aImplements['Hook\\Listener\Info']) === true) &&
			(isset($aParents['Hook\\Listener\InfoAbstract']) === true))
		{
			$this->aListenerInfo[] = new $sClass;
			return true;
		} // if

		if ((isset($aImplements['Hook\\Listener\Object']) === true) &&
			(isset($aParents['Hook\\Listener\ObjectAbstract']) === true))
		{
			$this->aListenerObject[] = new $sClass;
			return true;
		} // if

		$sError  = $sListener . ' does not implement or extend correct ';
		$sError .= 'interface or abstract class.!' . "\n";

		Log::getInstance()->writeLog(Log::HF_DEBUG, $sError);

		$this->sError .= $sError;

		return false;
	} // function
} // class
