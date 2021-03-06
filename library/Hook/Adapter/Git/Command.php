<?php
/**
 * Git- class for executing the git commands to collect data from commit.
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

use Hook\Adapter\CommandAbstract;
use Hook\Adapter\ArgumentsAbstract;

/**
 * Git- class for executing the git commands to collect data from commit.
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
class Command extends CommandAbstract
{
    /**
     * Against which commit to check.
     * @var string
     */
    protected $sAgainst = 'HEAD';

    /**
     * Sub command to get data..
     * @var string
     */
    protected $sSubCommand = ' diff ';

    /**
     * Last sha1 identifier..
     * @var string
     */
    protected $sLastSha1;

    /**
     * Initialize.
     * @param ArgumentsAbstract $oArguments Command line arguments.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function init(ArgumentsAbstract $oArguments)
    {
        $this->sRepository = $oArguments->getRepository();
        $this->sCommand    = $this->sBinPath . 'git';
        $this->sAgainst    = $oArguments->getTransaction();

        // On the post commit hook, be sure, that we get the only this commit
        // and not another commit.
        if ('post' === substr($oArguments->getSubType(), 0, 4)) {
            $this->sSubCommand = ' show ';
            $this->sAgainst    = $this->getLastSha1();
        }

        // On a pre* or the commit-msg hook, be sure,
        // that we only get what we are about to commit and not all changed files.
        if (('pre' === substr($oArguments->getSubType(), 0, 3)) ||
            ('com' === substr($oArguments->getSubType(), 0, 3))) {
            $this->sSubCommand .= ' --cached ';
        }
    }

    /**
     * Get the last sha1 number (post).
     * @return string
     */
    public function getLastSha1()
    {
        $sCommand  = $this->sCommand;
        $sCommand .= ' rev-parse HEAD';

        $aData = $this->execCommand($sCommand);

        return $aData[0];
    }

    /**
     * Gets the items of a commit that were changed (file, directory list).
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getCommitChanged()
    {
        $sCommand  = $this->sCommand;
        $sCommand .= $this->sSubCommand . '--raw ';
        $sCommand .= $this->sAgainst;

        return $this->execCommand($sCommand);
    }

    /**
     * Get the difference of that commit.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getCommitDiff()
    {
        $sCommand  = $this->sCommand;
        $sCommand .= $this->sSubCommand;
        $sCommand .= $this->sAgainst;

        return $this->execCommand($sCommand);
    }

    /**
     * Get the information of that commit (user, text message).
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getInfo()
    {
        return $this->getUser();
    }

    /**
     * Get information of that commit (user, text message).
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getUser()
    {
        $sCommand = $this->sCommand;
        $sCommand .= ' var GIT_AUTHOR_IDENT';

        return $this->execCommand($sCommand);
    }

    /**
     * Get information of that commit (user, text message).
     * @param string $sFile The file that contains the commit message (passed from arguments).
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getMessage($sFile)
    {
        $sFile = $this->sRepository . '/' . $sFile;

        return file($sFile, FILE_IGNORE_NEW_LINES);
    }

    /**
     * Write content from commited file.
     * @param string $sFile    File from TXN.
     * @param string $sTmpFile Temporary file on disk.
     * @return array
     */
    public function getContent($sFile, $sTmpFile)
    {
        $sFile = $this->sRepository . '/' . $sFile;

        copy($sFile, $sTmpFile);

        return file($sTmpFile, FILE_IGNORE_NEW_LINES);
    }

    /**
     * Check the result for errors.
     * @param array $aData Data from exec command.
     * @return array|bool
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function checkResult(array $aData)
    {
        // Empty must be an error.
        if (true === empty($aData)) {

            $this->bError = true;
            return array();
        }

        // Check command line output. If any of this words is in the result, an error occurred.
        if ((false !== strpos($aData[0], 'git --help')) ||
            (false !== strpos($aData[0], 'usage'))) {
            $this->bError = true;
            return $aData;
        }

        $this->bError = false;
        return $aData;
    }
}
