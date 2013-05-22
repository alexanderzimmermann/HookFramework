<?php
/**
 * Abstract class for the adapter for the git version control system..
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter;

/**
 * Abstract class for the adapter for the git version control system.
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
     * @param string $sCommand SVN Command.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function execCommand($sCommand)
    {
        $oLog = Log::getInstance();
        $oLog->writeLog(Log::HF_VARDUMP, 'command', $sCommand);

        exec($sCommand, $aData);

        $oLog->writeLog(Log::HF_VARDUMP, 'result lines', $aData);

        return $aData;
    }
}
