<?php
/**
 * Controller abstract class.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter;

use Hook\Adapter\Git\Controller as GitController;
use Hook\Adapter\Svn\Controller as SvnController;
use Hook\Core\Error;
use Hook\Core\File;
use Hook\Core\Response;
use Hook\Core\Config;
use Hook\Core\Log;
use Hook\Commit\Data;
use Hook\Listener\AbstractInfo;
use Hook\Listener\AbstractObject;

/**
 * Abstract class for the controller in the adapter.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
abstract class ControllerAbstract
{
    /**
     * Adapter Actions.
     * @var array
     */
    protected $aAdapterActions = array();

    /**
     * Main configuration object.
     * @var Config
     */
    protected $oConfig;

    /**
     * Arguments from hook call.
     * @var ArgumentsAbstract
     */
    protected $oArguments;

    /**
     * Log instance..
     * @var Log
     */
    protected $oLog;

    /**
     * Data Object.
     * @var Data
     */
    protected $oData;

    /**
     * Error-Object.
     * @var Error
     */
    protected $oError;

    /**
     * Response object.
     * @var Response
     */
    protected $oResponse;

    /**
     * File writer object.
     * @var File
     */
    protected $oFile;

    /**
     * List with Listener object for Info and object.
     * @var array
     */
    protected $aListener;

    /**
     * Shows usage of hook framework.
     * @return void
     */
    abstract public function showUsage();

    /**
     * Initialize controller.
     * @param Config   $oConfig   Main configuration.
     * @param Log      $oLog      The log we need to log debug information and errors.
     * @param Response $oResponse Response Object from hook.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function init(Config $oConfig, Log $oLog, Response $oResponse)
    {
        $oLog->writeLog(Log::HF_DEBUG, 'controller init');
        $this->oConfig   = $oConfig;
        $this->oResponse = $oResponse;

        return true;
    }

    /**
     * Start to parse the commit.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    abstract protected function parse();

    /**
     * Run.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function run()
    {
        $this->oLog->writeLog(Log::HF_INFO, 'controller run start');

        $this->oError = new Error();

        if (true === $this->parse()) {

            $this->runListenerInfo();
            $this->runListenerObject();
            $this->handleErrors();
            $this->shutDown();
        }

        $this->oLog->writeLog(Log::HF_INFO, 'controller run end');

        return $this->oResponse;
    }

    /**
     * Call info listener.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function runListenerInfo()
    {
        if (empty($this->aListener['info']) === true) {

            return;
        }

        $iMax = count($this->aListener['info']);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {

            $this->processInfoListener($this->aListener['info'][$iFor]);
        }
    }

    /**
     * Call Listener.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function runListenerObject()
    {
        if (true === empty($this->aListener['object'])) {
            return;
        }

        $iMax = count($this->aListener['object']);

        for ($iFor = 0; $iFor < $iMax; $iFor++) {

            $sLog = 'process object listener ';
            $sLog .= $this->aListener['object'][$iFor]->getListenerName();
            $this->oLog->writeLog(Log::HF_DEBUG, $sLog);

            $this->processObjectListener($this->aListener['object'][$iFor]);
        }
    }

    /**
     * Call Listener for Info.
     * @param AbstractInfo $oListener Listener.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function processInfoListener(AbstractInfo $oListener)
    {
        // No files, call listener once.
        $sLog  = 'process info listener '. $oListener->getListenerName();
        $this->oLog->writeLog(Log::HF_INFO, $sLog);

        $oInfo = $this->oData->getInfo();

        $oListener->processAction($oInfo);

        $this->oError->setListener($oListener->getListenerName());
        $this->oError->processActionInfo($oInfo);
    }

    /**
     * Execute Listener.
     * @param AbstractObject $oListener Listener Object.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function processObjectListener(AbstractObject $oListener)
    {
        $aObjects = $this->oData->getObjects($oListener);

        $this->oLog->writeLog(Log::HF_VARDUMP, 'files ' . count($aObjects));

        $iMax = count($aObjects);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {

            // Write the file to disk for the listener to "play" with it.
            $this->oFile->writeFile($aObjects[$iFor]);

            $this->oLog->writeLog(Log::HF_VARDUMP, $aObjects[$iFor]);

            $oListener->processAction($aObjects[$iFor]);

            $this->oError->setListener($oListener->getListenerName());
            $this->oError->processActionObject($aObjects[$iFor]);
        }
    }

    /**
     * Error handling of listener.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function handleErrors()
    {
        if (true === $this->oError->hasError()) {

            $sErrors = $this->oError->getMessages();

            $this->oResponse->setText($sErrors);
            $this->oResponse->setResult(1);

            return;
        }

        $this->oResponse->setText('');
        $this->oResponse->setResult(0);
    }

    /**
     * Shut Down the controller.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function shutDown()
    {
        // Cleanup files using destruct method of File object.
        unset($this->oFile);
    }

    /**
     * Determine the right controller.
     *
     * We simply check the little difference between git and subversion hook identifier.
     * That is the "." in the git hook identifier.
     * @param array $aArguments The arguments ship with this hook call.
     * @return GitController|SvnController
     */
    public static function factory(array $aArguments)
    {
        $sHook = $aArguments[(count($aArguments) - 1)];

        if (false === strpos($sHook, '.')) {
            return new SvnController($aArguments);
        } else {
            return new GitController($aArguments);
        }
    }
}
