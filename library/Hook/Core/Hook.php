<?php
/**
 * Hauptdatei fuer das Hook Framework.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Core;

use Hook\Adapter\Git\Controller as ControllerGit;
use Hook\Adapter\Svn\Controller as ControllerSvn;

use Hook\Core\Config;
use Hook\Core\Response;

/**
 * Main class for the Hook Framework.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Hook
{
    /**
     * Arguments Object.
     * @var array
     */
    private $aArguments;

    /**
     * Response object.
     * @var Response
     */
    private $oResponse;

    /**
     * Ini file settings.
     * @var Config
     */
    protected $oConfig;

    /**
     * Log instance.
     * @var Log
     */
    protected $oLog;

    /**
     * Constructor.
     * @param array $aArguments Command line arguments.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct(array $aArguments)
    {
        $this->aArguments = $aArguments;
        $this->oResponse  = new Response();
    }

    /**
     * Initialize hook.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function init()
    {
        // Main Configuration.
        $sConfigFile   = HF_ROOT . 'config.ini';
        $this->oConfig = new Config();
        $bConfigLoaded = $this->oConfig->loadConfigFile($sConfigFile);

        if (false === $bConfigLoaded) {

            $this->oResponse->setText('Config file ' . $sConfigFile . ' could not be loaded.');
            $this->oResponse->setResult(1);
            return false;
        }

        // Init log object.
        $this->oLog = Log::getInstance();
        $this->oLog->setLogFile($this->oConfig->getConfiguration('log', 'logfile'));
        $this->oLog->setLogMode($this->oConfig->getConfiguration('log', 'logmode'));
        $this->oLog->writeLog(Log::HF_VARDUMP, 'Arguments', $this->aArguments);

        return true;
    }

    /**
     * Run the hook.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function run()
    {
        try {
            // Initialize, false when errors occurred.
            if (false === $this->init()) {

                $this->oResponse->send();
                return $this->oResponse->getResult();
            }

            // To log that at least the Hook was called.
            $this->oLog->writeLog(Log::HF_INFO, 'hook run start');

            // TODO: Factory for the correct Adapter (git, svn).
            $oController = new ControllerSvn($this->aArguments);
            $oController->init($this->oConfig);
            $this->oResponse = $oController->run();

            $this->oLog->writeLog(Log::HF_INFO, 'hook response code: ' . $this->oResponse->getResult());

            // And we are done.
            $this->oLog->writeLog(Log::HF_INFO, 'hook run end');

            $this->oResponse->send();
            return $this->oResponse->getResult();

        } catch (Exception $oE) {

            $this->oLog->writeLog(Log::HF_INFO, 'hook error: ' . $oE->getMessage());

            $sMsg = $oE->getMessage() . ' ' . $oE->getFile() . ':' . $oE->getLine();

            $this->oResponse->setText($sMsg);
            $this->oResponse->send();

            return 1;
        }
    }
}
