<?php
/**
 * Git- class for executing the git commands to collect data from commit.
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

namespace Hook\Adapter\Git;

use Hook\Adapter\CommandAbstract;
use Hook\Adapter\Svn\Arguments;

/**
 * Git- class for executing the git commands to collect data from commit.
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
class Command extends CommandAbstract
{
    /**
     * Initialize.
     * @param Arguments $oArguments Command line arguments.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function init(Arguments $oArguments)
    {
        $this->sRepository = $oArguments->getRepository();
        $this->sCommand    = $this->sBinPath . 'git';
    }

    /**
     * Gets the items of a commit that were changed (file, directory list).
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getCommitChanged()
    {
        $sCommand = $this->sCommand;
        $sCommand .= ' diff --raw ';
        $sCommand .= $this->sAgainst;

        return $this->execCommand($sCommand);
    }

    /**
     * Get information of that commit (user, text message).
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getInfo()
    {
        $sCommand = $this->sCommand;
        $sCommand .= ' info';
        $sCommand .= $this->sLookParams;
        $sCommand .= ' ' . $this->sRepository;

        return $this->execCommand($sCommand);
    }

    /**
     * Get the difference of that commit.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getCommitDiff()
    {
        $sCommand = $this->sCommand;
        $sCommand .= ' diff';
        $sCommand .= $this->sLookParams;
        $sCommand .= ' ' . $this->sRepository;

        return $this->execCommand($sCommand);
    }

    /**
     * Write content from commited file.
     * @param string $sFile    File from TXN.
     * @param string $sTmpFile Temporary file on disk.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getContent($sFile, $sTmpFile)
    {
        $sCommand = $this->sCommand;
        $sCommand .= ' cat';
        $sCommand .= $this->sLookParams;
        $sCommand .= ' ' . $this->sRepository;
        $sCommand .= ' ' . $sFile;
        $sCommand .= ' > ' . $sTmpFile;

        return $this->execCommand($sCommand);
    }

    /**
     * Get list of properties to the item.
     * @param string $sItem Element for the properties (Directory or file).
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     * @since  1.0.0
     */
    public function getPropertyList($sItem)
    {
        $sCommand = $this->sCommand;
        $sCommand .= ' proplist';
        $sCommand .= $this->sLookParams;
        $sCommand .= ' ' . $this->sRepository;
        $sCommand .= ' ' . $sItem;

        return $this->execCommand($sCommand);
    }

    /**
     * Get the property value.
     * @param string $sItem     Element for the properties (Directory or file).
     * @param string $sProperty Name of property of value to get.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     * @since  1.0.0
     */
    public function getPropertyValue($sItem, $sProperty)
    {
        $sCommand = $this->sCommand;
        $sCommand .= ' propget';
        $sCommand .= $this->sLookParams;
        $sCommand .= ' ' . $this->sRepository;
        $sCommand .= ' ' . $sProperty;
        $sCommand .= ' ' . $sItem;

        return $this->execCommand($sCommand);
    }
}
