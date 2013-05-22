<?php
/**
 * Class for handling the arguments of a git hook call.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter\Git;

/**
 * Class for handling the arguments of a git hook call.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class Arguments
{
    /**
     * Valid hooks (complete name).
     * @see http://git-scm.com/book/en/Customizing-Git-Git-Hooks
     * @var array
     */
    private $aHooks = array(
                       'applypatch-msg', 'commit-msg', 'update', 'prepare-commit-msg',
                       'pre-rebase', 'pre-commit', 'pre-applypatch', 'pre-receive',
                       'post-update', 'post-receive', 'post-commit'
    );

    /**
     * Available svn actions for each type.
     * @var array
     */
    private $aActions = array(
                         'misc' => array(
                                    'applypatch-msg'     => array(
                                                             '', '',
                                                            ),
                                    'update'             => array(
                                                             'refname', 'oldrev', 'newrev'
                                                            ),
                                    'commit-msg'         => array(
                                                             ''
                                                            ),
                                    'prepare-commit-msg' => array(
                                                             ''
                                                            )
                         ),
                         'pre'  => array(
                                    'rebase'     => array(
                                                     'repos', 'txn'
                                                    ),
                                    'commit'     => array(
                                                     'repos', 'user', 'file'
                                                    ),
                                    'applypatch' => array(
                                                     'repos', 'rev', 'user', 'propname', 'action'
                                                    ),

                                    'receive'    => array(
                                                     'repos', 'user'
                                                    )
                                   ),

                         'post' => array(
                                    'commit'  => array(
                                                  'repos', 'rev'
                                                 ),
                                    'update'  => array(
                                                  'repos', 'user'
                                                 ),
                                    'receive' => array(
                                                  'repos', 'rev', 'user', 'propname', 'action'
                                                 )
                                   ),
                        );

    /**
     * Arguments of hook call.
     * @var array
     */
    private $aArguments;

    /**
     * arguments count.
     * @var integer
     */
    private $iArguments;

    /**
     * main type for hook call (start, pre, post).
     * @var string
     */
    private $sMainType;

    /**
     * Subtype for Hook (commit, lock).
     * @var string
     */
    private $sSubType;

    /**
     * Arguments Ok.
     * @var boolean
     */
    private $bArgumentsOk;

    /**
     * Repository path.
     * @var string
     */
    private $sRepository;

    /**
     * Repository name.
     * @var string
     */
    private $sRepositoryName;

    /**
     * Username of commit.
     * @var string
     */
    private $sUser;

    /**
     * Transaction number.
     * @var string
     */
    private $sTxn;

    /**
     * Revision number.
     * @var integer
     */
    private $iRev;

    /**
     * file of lock.
     * @var string
     */
    private $sFile;

    /**
     * Property Name.
     * @var string
     */
    private $sPropertyName;

    /**
     * Action for property.
     * @var string
     */
    private $sAction;

    /**
     * Internal error text.
     * @var string
     */
    private $sError;

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
     * Arguments Ok.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function argumentsOk()
    {
        $this->checkArguments();

        return $this->bArgumentsOk;
    }

    /**
     * Return complete hook type.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getMainHook()
    {
        return $this->sMainType . '-' . $this->sSubType;
    }

    /**
     * Return only main type string.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getMainType()
    {
        return $this->sMainType;
    }

    /**
     * Return subtype.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getSubType()
    {
        return $this->sSubType;
    }

    /**
     * Return repository path.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getRepository()
    {
        return $this->sRepository;
    }

    /**
     * Return the repository name.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getRepositoryName()
    {
        return $this->sRepositoryName;
    }

    /**
     * Return user.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getUser()
    {
        return $this->sUser;
    }

    /**
     * Returns action name.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getAction()
    {
        return $this->sAction;
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
     * Returns error text.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getError()
    {
        return $this->sError;
    }

    /**
     * Check the arguments.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkArguments()
    {
        if ($this->checkMainHook() === true) {
            if ($this->checkArgumentCount() === true) {
                if ($this->checkArgumentTypes() === true) {
                    $this->bArgumentsOk = true;

                    return;
                } // if
            } // if
        } // if

        $this->bArgumentsOk = false;
    }

    /**
     * Check if the initial hook call is correct.
     * The last element of the parameters should contain the correct value.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkMainHook()
    {
        if (true === empty($this->aArguments)) {
            $this->sError = 'Empty Arguments';

            return false;
        } // if

        $sMain = $this->aArguments[($this->iArguments - 1)];

        if (in_array($sMain, $this->aHooks) === false) {
            $this->sError .= 'MainHook ';

            return false;
        } // if

        $aHook           = explode('-', $sMain, 2);
        $this->sMainType = $aHook[0];
        $this->sSubType  = $aHook[1];

        // MainHook Argument entfernen.
        array_pop($this->aArguments);
        $this->iArguments--;

        return true;
    }

    /**
     * Compare count of arguments.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkArgumentCount()
    {
        $aTypes = $this->aActions[$this->sMainType][$this->sSubType];

        if ($this->iArguments === count($aTypes)) {
            return true;
        } // if

        $this->sError .= 'Argument Count ';

        return false;
    }

    /**
     * Check the arguments.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkArgumentTypes()
    {
        $iErrors = 0;
        $aTypes  = $this->aActions[$this->sMainType][$this->sSubType];

        foreach ($aTypes as $iIndex => $sType) {
            $sArgument = $this->aArguments[$iIndex];
            $bResult   = $this->checkType($sType, $sArgument);

            if (false === $bResult) {
                $iErrors++;
            } // if
        } // foreach

        if (0 === $iErrors) {
            return true;
        } // if

        return false;
    }

    /**
     * Call check routine after the type value.
     * @param string $sType     Type parameter.
     * @param string $sArgument Value of parameter.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkType($sType, $sArgument)
    {
        $sFunction = 'check' . ucfirst($sType);

        if (true === method_exists($this, $sFunction)) {
            return $this->$sFunction($sArgument);
        } // if

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
        if (true === file_exists($sRepository . '/format')) {
            $this->sRepository     = $sRepository;
            $this->sRepositoryName = basename($sRepository);

            return true;
        } // if

        $this->sError .= 'Repository ' . $sRepository . ' does not exists.';

        return false;
    }

    /**
     * Checks the user.
     * @param string $sUser Username.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkUser($sUser)
    {
        if (preg_match('/[a-z0-9]+/i', $sUser) > 0) {
            $this->sUser = $sUser;

            return true;
        } // if

        $this->sError .= 'User ';

        return false;
    }

    /**
     * Check the transaction number (z.B. 501-a, 501-11).
     * @param string $sTransaction Transaction number.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkTxn($sTransaction)
    {
        if (preg_match('/^[0-9]+\-([a-z0-9]{1,2}|[0-9]+)$/', $sTransaction) > 0) {
            $this->sTxn = $sTransaction;

            return true;
        } // if

        $this->sError .= 'Transaction ';

        return false;
    }

    /**
     * Checks the revision number (i.e. 501).
     * @param string $sRevision Revision number.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkRev($sRevision)
    {
        if (preg_match('/[0-9]+/', $sRevision) > 0) {
            $this->iRev = (int)$sRevision;

            return true;
        } // if

        $this->sError .= 'Revision ';

        return false;
    }

    /**
     * Check if file is correct.
     * @param string $sFile Filename.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkFile($sFile)
    {
        $this->sFile = $sFile;

        return true;
    }

    /**
     * Check property name.
     * @param string $sPropertyName Property name.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkPropname($sPropertyName)
    {
        $this->sPropertyName = $sPropertyName;

        return true;
    }

    /**
     * Check propset action.
     * @param string $sAction Action (A, M, D).
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkAction($sAction)
    {
        if (preg_match('/[AMD]+/i', $sAction) > 0) {
            $this->sAction = $sAction;

            return true;
        } // if

        $this->sError .= 'Action false, not (A, M, D)';

        return false;
    }
}