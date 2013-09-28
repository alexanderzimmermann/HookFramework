<?php
/**
 * Class for handling the arguments of a git hook call.
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

namespace Hook\Adapter\Git;

use Hook\Adapter\ArgumentsInterface;
use Hook\Adapter\ArgumentsAbstract;

/**
 * Class for handling the arguments of a git hook call.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class Arguments extends ArgumentsAbstract implements ArgumentsInterface
{
    /**
     * Delimiter.
     * @var string
     */
    protected $sDelimiter = '.';

    /**
     * Valid hooks (complete name).
     * @see http://git-scm.com/book/en/Customizing-Git-Git-Hooks
     * @var array
     */
    protected $aHooks = array(
                       'client.pre-commit', 'client.prepare-commit-msg', 'client.commit-msg', 'client.post-commit',
                       'client.applypatch-msg', 'client.pre-applypatch', 'client.post-applypatch',
                       'client.pre-rebase', 'client.post-checkout', 'client.post-merge',
                       'server.pre-receive', 'server.update', 'server.post-receive'
    );

    /**
     * Available git actions .
     * @var array
     */
    protected $aActions = array(
                         'client' => array(
                                      'pre-commit'         => array('repos', 'txn'),
                                      'prepare-commit-msg' => array('repos', 'txn', 'file', 'action'),
                                      'commit-msg'         => array('repos', 'txn', 'file'),
                                      'post-commit'        => array('repos'),
                                      // E-mail workflow hooks.
                                      'applypatch-msg'     => array('repos'),
                                      'pre-applypath'      => array('repos'),
                                      'post-applypatch'    => array('repos'),
                                      // Other client hooks
                                      'pre-rebase'         => array('repos'),
                                      'post-checkout'      => array('repos'),
                                      'post-merge'         => array('repos'),

                         ),
                         'server'  => array(
                                       'pre-receive'       => array('repos'),
                                       'update'            => array('repos'),
                                       'post-receive'      => array('repos'),
                                      ),
                        );

    /**
     * The file that contains the commit message.
     * @var string
     */
    private $sCommitMessageFile;

    /**
     * The commit message action identifier.
     * @var string
     */
    private $sCommitMessageAction;

    /**
     * Constructor.
     * @param array $aArguments Command line arguments.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct(array $aArguments)
    {
        // Delete first element (path file).
        array_shift($aArguments);

        $this->bArgumentsOk = false;
        $this->aArguments   = $aArguments;
        $this->iArguments   = count($aArguments);
    }

    /**
     * Returns all available sub actions from the main action.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getSubActions()
    {
        return array_keys($this->aActions[$this->sMainType]);
    }

    /**
     * Returns the parameter from commit-message for the commit message file.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getCommitMessageFile()
    {
        return $this->sCommitMessageFile;
    }

    /**
     * Returns the commit message action.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getCommitMessageAction()
    {
        return $this->sCommitMessageAction;
    }

    /**
     * Call check routine after the type value.
     * @param string $sType     Type parameter.
     * @param string $sArgument Value of parameter.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function checkType($sType, $sArgument)
    {
        $sFunction = 'check' . ucfirst($sType);

        if (true === method_exists($this, $sFunction)) {
            return $this->$sFunction($sArgument);
        }

        $bResult = false;
        $this->sError .= 'Check Type Error for ' . $sType;

        return $bResult;
    }

    /**
     * Check if the repository exists.
     * Just a simple test if the file format exists.
     * @param string $sRepository Repository path.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkRepos($sRepository)
    {
        if (true === file_exists($sRepository . '/.git/config')) {
            $this->sRepository     = $sRepository;
            $this->sRepositoryName = basename($sRepository);

            return true;
        }

        $this->sError .= 'Repository ' . $sRepository . ' does not exists.';

        return false;
    }

    /**
     * Check the transaction number (e.g. beb5ba1ee12722e1c2fa552d9f34bb096f5edcec).
     * @param string $sTransaction Transaction number.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkTxn($sTransaction)
    {
        if (preg_match('/^([0-9a-f]{40,40}|HEAD)$/', $sTransaction) > 0) {
            $this->sTxn = $sTransaction;

            return true;
        }

        $this->sError .= 'Transaction "' . $sTransaction . '" is not a valid hash.';

        return false;
    }

    /**
     * Check file parameter.
     * @param string $sFile Filename.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkFile($sFile)
    {
        $this->sCommitMessageFile = $sFile;
        return true;
    }

    /**
     * Check the action argument.
     * @param string $sAction Action.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkAction($sAction)
    {
        $this->sCommitMessageAction = $sAction;
        return true;
    }
}
