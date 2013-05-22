<?php
/**
 * Loading the different listener types.
 * @category   Core
 * @package    Listener
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter;

use \DirectoryIterator;
use Hook\Core\Arguments;
use Hook\Core\Config;
use Hook\Core\Log;

/**
 * Loading the different listener types.
 * The loader is responsible for some tasks.
 * - Parse the listener directory given to it.
 * - Includes the listener file.
 * - checks if the listener is implemented correctly and provides the needed methods (interface).
 * - creates the instance and checks if the listener registers data is correct.
 * If no one of this fits well, the listener is not used. The reason should be reported in the
 * error log. This should prevent from having errors.
 * @category   Core
 * @package    Listener
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
abstract class LoaderAbstract
{
    /**
     * Main Hook.
     * @var Arguments
     */
    protected $oArguments;

    /**
     * Configuration object with config from repository.
     * @var Config
     */
    protected $oConfig;

    /**
     * Path to the listener.
     * @var string
     */
    protected $sPath;

    /**
     * List of listener files from directory.
     * @var array
     */
    protected $aListenerFiles;

    /**
     * Namespace of listener.
     * @var array
     */
    protected $aListenerNamespace = array();

    /**
     * List of listener objects for file objects.
     * @var array
     */
    protected $aListenerObject;

    /**
     * List of listener object for info objects.
     * @var array
     */
    protected $aListenerInfo;

    /**
     * Error why listener failed.
     * @var string
     */
    protected $sError;

    /**
     * Constructor.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct()
    {
        $this->aListener       = array();
        $this->aListenerFiles  = array();
        $this->aListenerInfo   = array();
        $this->aListenerObject = array();
    }

    /**
     * Sets the Arguments.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setArguments(Arguments $oArguments)
    {
        $this->oArguments = $oArguments;
    }

    /**
     * Sets the configuration
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setConfiguration(Config $oConfig)
    {
        $this->oConfig = $oConfig;
    }

    /**
     * Set path where the listener are stored.
     * @param string $sPath Path where all listeners are stored.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setPath($sPath)
    {
        $this->sPath = $sPath;
    }

    /**
     * Init the Listener Parser.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    abstract public function init();

    /**
     * Return listener objects.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getListener()
    {
        $aListener = array();

        if (empty($this->aListenerInfo) === false) {
            $aListener['info'] = $this->aListenerInfo;
        } // if

        if (empty($this->aListenerObject) === false) {
            $aListener['object'] = $this->aListenerObject;
        } // if

        return $aListener;
    }

    /**
     * Read the files for the actual main hook action in directory.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function readDirectory()
    {
        $sType = ucfirst($this->oArguments->getMainType());

        // If directory does not exists, return.
        if (false === is_dir($this->sPath . $sType)) {
            return;
        } // if

        $oIterator = new \DirectoryIterator($this->sPath . $sType);
        $aListener = array();

        foreach ($oIterator as $oFile) {
            if (true === $oFile->isFile()) {
                if ('php' === $oFile->getExtension()) {
                    $aListener[] = $oFile->getPathname();
                } // if
            } // if
        } // foreach

        $this->aListenerFiles = $aListener;
    }

    /**
     * Register Call methods of info listener.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function registerListenerInfo()
    {
        $iMax = count($this->aListenerInfo);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {

            $bOk = $this->registerListenersInfo($this->aListenerInfo[$iFor]);

            if ($bOk === false) {

                unset($this->aListenerInfo[$iFor]);
            } // if
        } // for

        $this->aListenerInfo = array_values($this->aListenerInfo);
    }

    /**
     * Register Call methods of object listener.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function registerListenerObject()
    {
        $iMax = count($this->aListenerObject);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $oListenerObject = $this->aListenerObject[$iFor];

            $bOk = $this->registerListenersObject($oListenerObject);

            if ($bOk === false) {
                unset($this->aListenerObject[$iFor]);
            } // if
        } // for

        $this->aListenerObject = array_values($this->aListenerObject);
    }

    /**
     * Register values for info listener and check it.
     * @param AbstractInfo $oListener Name of listener objects.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function registerListenersInfo(AbstractInfo $oListener)
    {
        $sRegister = $oListener->register();
        $sListener = $oListener->getListenerName();

        // Correct Types?
        if (is_string($sRegister) === false) {
            $this->sError .= $sListener . ' Register not a String for InfoType';

            return false;
        } // if

        // Types empty?
        if ($sRegister === '') {
            $this->sError .= $sListener . ' Error Register String Empty';

            return false;
        } // if

        // Correct values?
        $aSvnActions = $this->oArguments->getSubActions();

        if (in_array($sRegister, $aSvnActions) === false) {
            $this->sError .= $sListener . ' Register Action ';
            $this->sError .= $sRegister . ' not available!';

            return false;
        } // if

        return true;
    }

    /**
     * Register values for object listener and check it.
     * @param AbstractObject $oListener Name des Listener Objekts.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function registerListenersObject(AbstractObject $oListener)
    {
        $aRegister = $oListener->register();
        $sListener = $oListener->getListenerName();

        // All needed values available.
        if ((isset($aRegister['action']) === false) ||
            (isset($aRegister['extensions']) === false) ||
            (isset($aRegister['fileaction']) === false)
        ) {
            $this->sError .= $sListener . ' Error Register Key Elements';

            return false;
        } // if

        // Correct type?
        if ((is_string($aRegister['action']) === false) ||
            (is_array($aRegister['extensions']) === false) ||
            (is_array($aRegister['fileaction']) === false)
        ) {
            $this->sError .= $sListener . ' Error Register Array Types';

            return false;
        } // if

        // Type empty?
        if (($aRegister['action'] === '') &&
            (empty($aRegister['extension']) === true) &&
            (empty($aRegister['fileaction']) === true)
        ) {
            $this->sError .= $sListener . ' Error Register Array Empty';

            return false;
        } // if

        // Valid values?
        $sAction     = $aRegister['action'];
        $aSvnActions = $this->oArguments->getSubActions();

        if (in_array($sAction, $aSvnActions) === false) {
            $this->sError .= $sListener . ' Register Action ';
            $this->sError .= $sAction . ' not available!';

            return false;
        } // if

        return true;
    }

    /**
     * Check if the listener can be used.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function checkListener()
    {
        $aListener = array();

        $iMax = count($this->aListenerFiles);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            if (true === file_exists($this->aListenerFiles[$iFor])) {
                // Include the listener file.
                include_once $this->aListenerFiles[$iFor];

                // Extract listener name from filename.
                $sListener = basename($this->aListenerFiles[$iFor]);
                $sListener = str_replace('.php', '', $sListener);

                // Check the listener is available after including. If the file contains other code
                // like Helper for a listener, we don't want to use it..
                // Create the listener and put in category (info, object) list.
                try {
                    if (false === $this->checkListenerImplementation($sListener)) {
                        unset($this->aListenerFiles[$iFor]);
                    } else {
                        $aListener[] = $sListener;
                    } // if
                } catch (\Exception $oException) {
                    $this->sError .= $oException->getMessage() . PHP_EOL
                        . $oException->getTraceAsString() . PHP_EOL;
                } // try
            } // if
        } // for

        // Sets the listener.
        $this->aListenerFiles = $aListener;

        Log::getInstance()->writeLog(Log::HF_VARDUMP, 'Accepted Listeners', $this->aListenerFiles);
    }

    /**
     * Check the included listener implements the required stuff.
     * @param string $sListener Name des Listenere-Objekts.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function checkListenerImplementation($sListener)
    {
        // Class exists?
        $sClass = $this->oArguments->getRepositoryName() . '\\'
            . ucfirst($this->oArguments->getMainType()) . '\\' . $sListener;

        if (false === class_exists($sClass, false)) {
            $sMsg = $sClass . ' class and filename doesn\'t match!';
            $this->sError .= $sMsg . "\n";

            Log::getInstance()->writeLog(Log::HF_DEBUG, $sMsg);

            return false;
        } // if

        // Check for correct interface and abstract class.
        $aImplements = class_implements($sClass);
        $aParents    = class_parents($sClass);

        if ((isset($aImplements['Hook\\Listener\InfoInterface']) === true) &&
            (isset($aParents['Hook\\Listener\AbstractInfo']) === true)
        ) {
            $this->aListenerInfo[] = $this->createClass($sClass, $sListener);

            return true;
        } // if

        if ((isset($aImplements['Hook\\Listener\ObjectInterface']) === true) &&
            (isset($aParents['Hook\\Listener\AbstractObject']) === true)
        ) {
            $this->aListenerObject[] = $this->createClass($sClass, $sListener);

            return true;
        } // if

        $sError = $sListener . ' does not implement or extend correct ';
        $sError .= 'interface or abstract class.!' . "\n";

        Log::getInstance()->writeLog(Log::HF_DEBUG, $sError);

        $this->sError .= $sError;

        return false;
    }

    /**
     * Create the listener.
     * @param string $sClass    Class name of listener.
     * @param string $sListener Name of the listener (File, Class).
     * @return ListenerInterface
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function createClass($sClass, $sListener)
    {
        $oClass = new $sClass;

        $sMain = $this->oArguments->getMainType();
        $aCfg  = $this->oConfig->getConfiguration(ucfirst($sMain), $sListener);

        if (false !== $aCfg) {
            $oClass->setConfiguration($aCfg);
        } // if

        return $oClass;
    }
}
