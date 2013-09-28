<?php
/**
 * This is the main controller for the subversion adapter.
 * @category   Adapter
 * @package    Svn
 * @subpackage Svn
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter\Svn;

use \Exception;
use Hook\Adapter\ControllerAbstract;
use Hook\Adapter\Svn\Arguments;
use Hook\Adapter\Svn\Command;
use Hook\Adapter\Svn\Parser\Changed;
use Hook\Adapter\Svn\Parser\Info;
use Hook\Adapter\Svn\Parser\Parser;
use Hook\Commit\Data;
use Hook\Commit\Object;
use Hook\Core\Config;
use Hook\Core\File;
use Hook\Core\Log;
use Hook\Core\Response;

/**
 * This is the main controller for the subversion adapter.
 * @category   Adapter
 * @package    Svn
 * @subpackage Svn
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class Controller extends ControllerAbstract
{
    /**
     * Available adapter actions.
     * @var array
     */
    protected $aAdapterActions = array('A', 'U', 'D');

    /**
     * git command object.
     * @var Command
     */
    protected $oCommand;

    /**
     * Constructor.
     * @param Arguments $oArguments Arguments from command line.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct(Arguments $oArguments)
    {
        $this->oArguments = $oArguments;
    }

    /**
     * Initialize controller.
     * - Check Arguments.
     * - Create the command object.
     * - Init the repository data.
     * - Load the listener
     * @param Config   $oConfig   Main configuration.
     * @param Log      $oLog      The log we need to log debug information and errors.
     * @param Response $oResponse Response Object from hook.
     * @throws \Exception
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function init(Config $oConfig, Log $oLog, Response $oResponse)
    {
        parent::init($oConfig, $oLog, $oResponse);
        $this->oLog = $oLog;

        if (false === $this->oArguments->argumentsOk()) {

            $this->oResponse->setText($this->showUsage());
            $sError = 'Arguments Error. ' .  $this->oArguments->getError();
            $this->oLog->writeLog(Log::HF_INFO, $sError);

            throw new Exception($sError);
        }

        $this->oLog->writeLog(Log::HF_DEBUG, 'controller init Arguments Ok');

        // Initialize Repository.
        $sDirectory = $this->initRepository();

        // Create command object.
        $this->oCommand = new Command($this->oConfig->getConfiguration('vcs', 'binary_path'));
        $this->oCommand->init($this->oArguments);

        // A file writer that handles all temporary created files.
        $this->oFile = new File($this->oCommand, $this->oLog);

        // Loader.
        return $this->initLoader($sDirectory);
    }

    /**
     * Init the listener loader and load the listener.
     * @param string $sDirectory Directory of hookframework repository.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function initLoader($sDirectory)
    {
        $this->oLog->writeLog(Log::HF_INFO, 'controller load Listener');

        // Parse listener in directory.
        $bResult = true;
        $oLoader = new Loader();
        $oLoader->setArguments($this->oArguments);
        $oLoader->setConfiguration($this->oConfig);
        $oLoader->setPath($sDirectory);
        $oLoader->init();

        $this->aListener = $oLoader->getListener();

        // No listener available? Then abort here (performance).
        if (true === empty($this->aListener)) {

            // Check if there are no files or files found but with bad implementations.
            $aFiles = $oLoader->getListenerFiles();
            if (true === empty($aFiles)) {

                $sMessage = 'No listener found at ' . $sDirectory . '. Abort!';
                $this->oLog->writeLog(Log::HF_DEBUG, $sMessage);
                $this->oResponse->setResult(0);

                $bResult = false;
            } else {

                $this->oLog->writeLog(Log::HF_DEBUG, 'controller errors: ' . $oLoader->getErrors());
                $this->oResponse->setResult(0);

                $bResult = false;
            }
        }

        $this->oLog->writeLog(Log::HF_DEBUG, 'controller errors: ' . $oLoader->getErrors());
        $this->oLog->writeLog(Log::HF_VARDUMP, 'Accepted Listeners', $oLoader->getListenerFiles());
        $this->oLog->writeLog(Log::HF_INFO, 'controller Listener loaded');
        unset($oLoader);

        return $bResult;
    }

    /**
     * Start to parse the commit, info and objects.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function parse()
    {
        $sTxn        = $this->oArguments->getTransaction();
        $iRev        = $this->oArguments->getRevision();
        $this->oData = new Data($this->aAdapterActions);

        $oInfo = new Info();

        // Only start has limited information.
        if ($this->oArguments->getMainType() === 'start') {

            $aInfo[0] = $this->oArguments->getUser();
            $aInfo[1] = date('Y-m-d H:i:s', time());
            $aInfo[2] = 24;
            $aInfo[3] = 'No Message in Start Hook';

            $this->oData->setInfo($oInfo->parse($aInfo, $sTxn, $iRev));

            return true;
        }

        // First contact.
        $aInfo = $this->oCommand->getInfo();

        // If an error occurred we abort here.
        if (true === $this->oCommand->hasError()) {

            $this->oResponse->setResult(1);
            $this->oResponse->setText(implode($aInfo));

            return false;
        }

        // Parse info from commit.
        $this->oData->setInfo($oInfo->parse($aInfo, $sTxn, $iRev));

        $aDiffLines = $this->oCommand->getCommitDiff();

        // Parse array with the changed items.
        $oChanged = new Changed();
        $oChanged->parseFiles($this->oCommand->getCommitChanged());

        $oParser = new Parser($oChanged->getFiles(), $aDiffLines);
        $oParser->parse();

        $this->createObjects($oChanged, $oParser);

        return true;
    }

    /**
     * Creating the data for the listener.
     * @param Changed $oChanged Changed object with all changed items in that commit.
     * @param Parser  $oParser  Parser objects.
     * @return void
     * @author   Alexander Zimmermann <alex@azimmermann.com>
     */
    private function createObjects(Changed $oChanged, Parser $oParser)
    {
        $aProperties = $oParser->getProperties();
        $aLines      = $oParser->getLines();

        // Values for all items.
        $sTxn = $this->oArguments->getTransaction();
        $iRev = $this->oArguments->getRevision();

        // Get the pre parsed items.
        $oItems = $oChanged->getObjects();

        // The info object to store in each item.
        $oInfo = $this->oData->getInfo();

        $aObjects = array();
        foreach ($oItems as $iFor => $aData) {

            $aData['txn']   = $sTxn;
            $aData['rev']   = $iRev;
            $aData['props'] = array();
            $aData['lines'] = null;
            $aData['info']  = $oInfo;

            if (true === isset($aProperties[$iFor])) {
                $aData['props'] = $aProperties[$iFor];
            }

            if (true === isset($aLines[$iFor])) {
                $aData['lines'] = $aLines[$iFor];
            }

            // Create object and collect it to store it in the info object.
            $oObject    = new Object($aData);
            $aObjects[] = $oObject;

            $this->oData->addObject($oObject);
        }

        // Set the commited objects for info listener.
        $this->oData->getInfo()->setObjects($aObjects);
    }

    /**
     * Show usage.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function showUsage()
    {
        $sMainType = $this->oArguments->getMainType();
        $sSubType  = $this->oArguments->getSubType();
        $oUsage    = new Usage($sMainType, $sSubType);

        return $oUsage->getUsage();
    }
}
