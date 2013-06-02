<?php
/**
 * Class to handle the temporary created files.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Core;

use Hook\Commit\Object;
use Hook\Adapter\CommandInterface;

/**
 * Class to handle the temporary created files.
 *
 * If the file was already added by other listeners its not written next time, the file already
 * created is used.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 0.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class File
{
    /**
     * Written files.
     * @var array
     */
    private $aFiles = array();

    /**
     * VCS Command object.
     * @var CommandInterface
     */
    private $oCommand;

    /**
     * Log object.
     * @var Log
     */
    private $oLog;

    /**
     * Constructor
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct(CommandInterface $oCommand, Log $oLog)
    {
        $this->oCommand = $oCommand;
        $this->oLog     = $oLog;
    }

    /**
     * Write file to disk.
     * @param \Hook\Commit\Object|Object $oObject File object.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function writeFile(Object $oObject)
    {
        $sFile    = $oObject->getObjectPath();
        $sTmpFile = $oObject->getTmpObjectPath();

        $sLog = 'process file "' . $sFile . '"';
        $this->oLog->writeLog(Log::HF_INFO, $sLog);

        if (false === $this->hasFile($sTmpFile)) {

            $this->oCommand->getContent($sFile, $sTmpFile);
        }
    }

    /**
     * If the file is already stored, don't get the content again.
     * @param string $sFile Created file.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function hasFile($sFile)
    {
        if (in_array($sFile, $this->aFiles) === false) {

            $this->aFiles[] = $sFile;
            return false;
        }

        return true;
    }

    /**
     * Destruct, cleanup files that are created.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __destruct()
    {
        $iMax = count($this->aFiles);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {

            if (true === file_exists($this->aFiles[$iFor])) {

                unlink($this->aFiles[$iFor]);
            }

            $sMessage = 'delete: ' . $this->aFiles[$iFor];
            $this->oLog->writeLog(Log::HF_DEBUG, $sMessage);
        }
    }
}
