<?php
/**
 * Abstract class for the adapter for the git version control system..
 * @category   Adapter
 * @package    Adapter
 * @subpackage Adapter
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter;

use Hook\Core\Log;

/**
 * Abstract class for the adapter for the git version control system.
 * @category   Adapter
 * @package    Adapter
 * @subpackage Adapter
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
abstract class CommandAbstract implements CommandInterface
{
    /**
     * Path to the executable.
     * @var string
     */
    protected $sBinPath = '/usr/bin/';

    /**
     * Version control system command.
     * @var string
     */
    protected $sCommand = '';

    /**
     * Error during command execution.
     * @var boolean
     */
    protected $bError;

    /**
     * Repository.
     * @var string
     */
    protected $sRepository;

    /**
     * Constructor.
     * @param string $sBinPath Path to the subversion executable.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct($sBinPath)
    {
        $this->sBinPath = $sBinPath;
    }

    /**
     * Execute the svn command line.
     * @param string $sCommand VCS Command.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function execCommand($sCommand)
    {
        $oLog = Log::getInstance('repository');
        $oLog->writeLog(Log::HF_VARDUMP, 'command', $sCommand);

        exec($sCommand, $aData);

        // Check the result for errors.
        $aData = $this->checkResult($aData);

        $oLog->writeLog(Log::HF_VARDUMP, 'result lines', $aData);

        return $aData;
    }

    /**
     * Check the result for errors.
     * @param array $aData Data from exec command.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    abstract protected function checkResult(array $aData);

    /**
     * Indicates whether errors have occurred.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function hasError()
    {
        return $this->bError;
    }
}
