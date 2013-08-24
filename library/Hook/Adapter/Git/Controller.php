<?php
/**
 *  This is the main controller for the git adapter.
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
     * @param array $aArguments Arguments from command line.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct(array $aArguments)
    {
        $this->oArguments = new Arguments($aArguments);
    }

    /**
     * Initialize controller.
     * - Check Arguments.
     * - Create the command object.
     * - Init the repository data.
     * - Load the listener
     * @param Config $oConfig Main configuration.
     * @param Log    $oLog    The log we need to log debug information and errors.
     * @throws \Exception
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function init(Config $oConfig, Log $oLog)
    {
        parent::init($oConfig, $oLog);
        $this->oLog = $oLog;

        if (false === $this->oArguments->argumentsOk()) {

            $this->showUsage();
            $this->oLog->writeLog(Log::HF_INFO, 'Arguments Error');
            throw new Exception('Arguments Error. ' . $this->oArguments->getError());
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
     * Initialize repository stuff.
     * @throws Exception
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function initRepository()
    {
        // Check if there is a repository path.
        $sRepositoryDir = $this->oConfig->getConfiguration('path', 'repositories');
        $sLogMode       = $this->oConfig->getConfiguration('log', 'logmode');

        // Fallback, set the shipped repository Example.
        if (false === $sRepositoryDir) {

            $sRepositoryDir = realpath(HF_ROOT . 'Repositories/');
        }

        $sDirectory = $sRepositoryDir . $this->oArguments->getRepositoryName() . '/';

        if (false === is_dir($sDirectory)) {

            // Use the Listener that come with the hookframework.
            $sDirectory = $sRepositoryDir . 'ExampleSvn/';

            // If this directory is missing, then we are screwed.
            if (false === is_dir($sDirectory)) {

                throw new Exception('Build-in repository is missing');
            }
        }

        // Load the configuration file of the repository.
        $sFile = $sDirectory . 'config.ini';
        if (false === file_exists($sFile)) {
            $sFile = $sDirectory . 'config-dist.ini';
        }

        $this->oConfig = new Config();
        $this->oConfig->loadConfigFile($sFile);

        // Check if a common.log file is available.
        $sFile = $sDirectory . 'logs/common.log';

        if ((true === is_file($sFile)) &&
            (true === is_writable($sFile))) {

            // Get another Log instance for the repository.
            $this->oLog = Log::getInstance('repository');

            // Change log file if a separate exists for the repository.
            $this->oLog->setLogFile($sFile);
            $this->oLog->setLogMode($sLogMode);
        }

        return $sDirectory;
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

            $aFiles = $oLoader->getListenerFiles();
            if (true === empty($aFiles)) {

                $sMessage = 'No listener found at ' . $sDirectory . '. Abort!';
                $this->oLog->writeLog(Log::HF_DEBUG, $sMessage);
                $this->oResponse->setResult(0);

                $bResult = false;
            } else {

                $this->oLog->writeLog(Log::HF_DEBUG, $oLoader->getErrors());

                $bResult = false;
            }
        }

        $this->oLog->writeLog(Log::HF_DEBUG, $oLoader->getErrors());
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
        $oInfo = new Info();
        $aInfo = $this->oCommand->getInfo();

        // If an error occurred we abort here.
        if (true === $this->oCommand->hasError()) {

            $this->oResponse->setResult(1);
            $this->oResponse->setText(implode($aInfo));

            return false;
        }

        $this->oData->setInfo($oInfo->parse($aInfo, $sTxn, 0));

        // Parse array with the changed items.
        $oChanged = new Changed();
        $oChanged->parseFiles($this->oCommand->getCommitChanged());

        $aDiffLines = $this->oCommand->getCommitDiff();
        $oParser    = new Parser($oChanged->getFiles(), $aDiffLines);
        $oParser->parse();

        $this->createObjects($oChanged, $oParser);

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

        echo $oUsage->getUsage();
    }
}
