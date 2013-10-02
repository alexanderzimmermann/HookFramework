<?php
/**
 * This is the main controller for the git adapter.
 * @category   Adapter
 * @package    Git
 * @subpackage Git
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter\Git;

use \Exception;
use Hook\Adapter\ControllerAbstract;
use Hook\Adapter\Git\Arguments;
use Hook\Adapter\Git\Command;
use Hook\Adapter\Git\Parser\Changed;
use Hook\Adapter\Git\Parser\Info;
use Hook\Adapter\Git\Parser\Parser;
use Hook\Commit\Data;
use Hook\Commit\Object;
use Hook\Core\Config;
use Hook\Core\File;
use Hook\Core\Log;
use Hook\Core\Response;

/**
 * This is the main controller for the git adapter.
 * @category   Adapter
 * @package    Git
 * @subpackage Git
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
    protected $aAdapterActions = array('M', 'C', 'R', 'A', 'D', 'U');

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
     * Start parsing
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parse()
    {
        $this->oLog->writeLog(Log::HF_INFO, 'controller parse start');

        $sTxn        = $this->oArguments->getTransaction();
        $this->oData = new Data($this->aAdapterActions);

        // First contact.
        $oInfo = new Info($sTxn);
        $aUser = $this->oCommand->getUser();

        // If an error occurred we abort here.
        if (true === $this->oCommand->hasError()) {

            $this->oResponse->setResult(1);
            $this->oResponse->setText(implode($aUser));

            return false;
        }

        // In message hooks, get the message text.
        $aMsg = array();
        if (false !== strpos($this->oArguments->getSubType(), 'msg')) {

            $sFile = $this->oArguments->getCommitMessageFile();
            $aMsg  = $this->oCommand->getMessage($sFile);
        }

        $oInfo->parseUser($aUser);
        $oInfo->parseMessage($aMsg);
        $this->oData->setInfo($oInfo->getInfoObject());

        // Parse array with the changed items.
        $aChanged = $this->oCommand->getCommitChanged();
        $oChanged = new Changed();

        // On post hooks, remove the header.
        if ('post' === substr($this->oArguments->getSubType(), 0, 4)) {
            $aChanged = array_slice($aChanged, 6);
        }

        // Parse files.
        $oChanged->parseFiles($aChanged);

        // Log.
        $aFiles = $oChanged->getFiles();
        $iFiles = count($aFiles);
        $this->oLog->writeLog(Log::HF_VARDUMP, 'controller parse found ' . $iFiles . ' files.');

        if (0 > $iFiles) {
            $aDiffLines = $this->oCommand->getCommitDiff();
            $oParser    = new Parser($aFiles, $aDiffLines);
            $oParser->parse();
            $this->createObjects($oChanged, $oParser);
        }

        $this->oLog->writeLog(Log::HF_INFO, 'controller parse end');

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
        $aLines = $oParser->getLines();

        // Values for all items.
        $sTxn = $this->oArguments->getTransaction();

        // Get the pre parsed items.
        $oItems = $oChanged->getObjects();

        // The info object to store in each item.
        $oInfo = $this->oData->getInfo();

        $aObjects = array();
        foreach ($oItems as $iFor => $aData) {

            $aData['txn']   = $sTxn;
            $aData['props'] = array();
            $aData['lines'] = null;
            $aData['info']  = $oInfo;

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
