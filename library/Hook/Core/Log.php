<?php
/**
 * Little log object.
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

use \ArrayObject;

/**
 * Little log object.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Log
{
    /**
     * Just infos, normal protocol.
     */
    const HF_INFO = 1;

    /**
     * For a simple debug mode..
     */
    const HF_DEBUG = 2;

    /**
     * Need some dumps for debugging.
     */
    const HF_VARDUMP = 3;

    /**
     * List of Log objects.
     * @var ArrayObject
     */
    static private $oInstances = null;

    /**
     * Log Mode.
     * @var integer
     */
    private $iLogMode;

    /**
     * Resource to log file.
     * @var resource
     */
    private $rFile;

    /**
     * Clone not allowed.
     * @throws \Exception
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __clone()
    {
        throw new \Exception('Not allowed');
    }

    /**
     * Get the log instance.
     * @param string $sInstance Log instance to init and return.
     * @return Log
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public static function getInstance($sInstance = 'default')
    {
        if (null === self::$oInstances) {

            self::$oInstances = new ArrayObject();
        }

        if (false === self::$oInstances->offsetExists($sInstance)) {

            $oLog = new self;
            self::$oInstances->offsetSet($sInstance, $oLog);
        } else {

            $oLog = self::$oInstances->offsetGet($sInstance);
        }

        return $oLog;
    }

    /**
     * Set the logfile name.
     * @param string $sFile Log file.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setLogFile($sFile)
    {
        $this->rFile = fopen($sFile, 'a+');

        fwrite($this->rFile, str_repeat('=', 80) . "\n");
        fwrite($this->rFile, str_repeat(' ', 20) . date('Y-m-d H:i:s') . "\n");
        fwrite($this->rFile, str_repeat('=', 80) . "\n");
    }

    /**
     * Sets the log mode.
     * @param integer $iLogMode Log mode.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setLogMode($iLogMode = self::HF_INFO)
    {
        $this->iLogMode = $iLogMode;
    }

    /**
     * Write log.
     * @param integer $iLogMode Log mode.
     * @param string  $sHeadMsg Headline for var.
     * @param mixed   $mVar     Variable for Debug.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function writeLog($iLogMode, $sHeadMsg = '', $mVar = null)
    {
        if ($this->iLogMode < $iLogMode) {
            return;
        }

        $sLogLine = $sHeadMsg . "\n";

        if ($mVar !== null) {
            $sVarDump = var_export($mVar, true);
            $sLogLine .= $sVarDump . "\n";
        }

        $sLogLine .= str_repeat('-', 40) . "\n";

        fwrite($this->rFile, $sLogLine);
    }
}
