<?php
/**
 * Loading the different listener types.
 * @category   Hook
 * @package    Svn
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter;

use \DirectoryIterator;
use Hook\Adapter\ArgumentsAbstract;
use Hook\Core\Config;

use Hook\Listener\AbstractInfo;
use Hook\Listener\AbstractObject;

/**
 * Loading the different listener types.
 * The loader is responsible for some tasks.
 * - Parse the listener directory given to it.
 * - Includes the listener file.
 * - Checks if the listener is implemented correctly and provides the needed methods (interface).
 * - Creates the object and checks, if the listener register data is correct.
 * If one of this checks fails, the listener will not be used.
 * The reason should be reported in the error log. This should prevent from having errors.
 * @category   Hook
 * @package    Svn
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
abstract class LoaderAbstract
{
    /**
     * Main Hook.
     * @var ArgumentsAbstract
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
     * List of listener object for info objects.
     * @var array
     */
    protected $aListenerInfo;

    /**
     * List of listener objects for file objects.
     * @var array
     */
    protected $aListenerObject;

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
     * @param ArgumentsAbstract $oArguments The argument object.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setArguments(ArgumentsAbstract $oArguments)
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
     * Get the used listener files.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getListenerFiles()
    {
        return $this->aListenerFiles;
    }

    /**
     * Return listener objects.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getListener()
    {
        $aListener = array();

        if (false === empty($this->aListenerInfo)) {
            $aListener['info'] = $this->aListenerInfo;
        }

        if (false === empty($this->aListenerObject)) {
            $aListener['object'] = $this->aListenerObject;
        }

        return $aListener;
    }

    /**
     * Get the errors that occur during loading the listener files
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getErrors()
    {
        return $this->sError;
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
        }

        $oIterator = new \DirectoryIterator($this->sPath . $sType);
        $aListener = array();

        /** @var \SplFileInfo $oFile */
        foreach ($oIterator as $oFile) {
            if (true === $oFile->isFile()) {
                if ('php' === $oFile->getExtension()) {
                    $aListener[] = $oFile->getPathname();
                }
            }
        }

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
            }
        }

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
            }
        }

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
            $this->sError .= $sListener . ' Register not a String for InfoType' . PHP_EOL;

            return false;
        }

        // Types empty?
        if ($sRegister === '') {
            $this->sError .= $sListener . ' Error Register String Empty' . PHP_EOL;

            return false;
        }

        // Correct register values? @todo Is like in the registerlistnerobject cpd
        $aVcsActions = $this->oArguments->getSubActions();

        if (in_array($sRegister, $aVcsActions) === false) {
            $this->sError .= '`' . $sListener . '` register action demands ';
            $this->sError .= '`' . $sRegister . '` but not available in vcs sub actions!' . PHP_EOL;

            return false;
        }

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
            (isset($aRegister['fileaction']) === false)) {

            $this->sError .= $sListener . ' Error Register Key Elements' . PHP_EOL;

            return false;
        }

        // Correct type?
        if ((is_string($aRegister['action']) === false) ||
            (is_array($aRegister['extensions']) === false) ||
            (is_array($aRegister['fileaction']) === false)
        ) {
            $this->sError .= $sListener . ' Error Register Array Types' . PHP_EOL;

            return false;
        }

        // Type empty?
        if (($aRegister['action'] === '') &&
            (empty($aRegister['extension']) === true) &&
            (empty($aRegister['fileaction']) === true)
        ) {
            $this->sError .= $sListener . ' Error Register Array Empty' . PHP_EOL;

            return false;
        }

        // Valid values?
        $sAction     = $aRegister['action'];
        $aVcsActions = $this->oArguments->getSubActions();

        if (in_array($sAction, $aVcsActions) === false) {
            $this->sError .= '`' . $sListener . '` register action demands ';
            $this->sError .= '`' . $sAction . '` but not available in vcs sub actions!' . PHP_EOL;

            return false;
        }

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

            // No need to check files available. They came with DirectoryIterator.
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
                }
            } catch (\Exception $oException) {
                $this->sError .= 'Failed to check listener' . PHP_EOL
                               . $oException->getMessage() . PHP_EOL
                               . $oException->getTraceAsString() . PHP_EOL;
            }
        }

        // Sets the listener.
        $this->aListenerFiles = $aListener;
    }

    /**
     * Check the included listener implements the required stuff.
     * @param string $sListener Name of listener object.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function checkListenerImplementation($sListener)
    {
        // Class exists?
        $sClass = $this->oArguments->getRepositoryName() . '\\'
            . ucfirst($this->oArguments->getMainType()) . '\\' . $sListener;

        if (false === class_exists($sClass, false)) {
            $sMsg = $sClass . ' class, namespace or filename doesn\'t match!' .PHP_EOL;
            $this->sError .= $sMsg . "\n";

            return false;
        }

        // Check for correct interface and abstract class.
        $aImplements = class_implements($sClass);
        $aParents    = class_parents($sClass);

        if ((isset($aImplements['Hook\\Listener\InfoInterface']) === true) &&
            (isset($aParents['Hook\\Listener\AbstractInfo']) === true)
        ) {
            $this->aListenerInfo[] = $this->createClass($sClass, $sListener);

            return true;
        }

        if ((isset($aImplements['Hook\\Listener\ObjectInterface']) === true) &&
            (isset($aParents['Hook\\Listener\AbstractObject']) === true)
        ) {
            $this->aListenerObject[] = $this->createClass($sClass, $sListener);

            return true;
        }

        $sError = $sListener . ' does not implement or extend correct ';
        $sError .= 'interface or abstract class.!' . PHP_EOL;

        $this->sError .= $sError;

        return false;
    }

    /**
     * Create the listener class.
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
        }

        return $oClass;
    }
}
