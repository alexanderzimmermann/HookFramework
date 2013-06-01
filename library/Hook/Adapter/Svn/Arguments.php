<?php
/**
 * Class for handling the arguments of a hook call.
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

namespace Hook\Adapter\Svn;

use Hook\Adapter\ArgumentsInterface;

/**
 * Class for handling the arguments of a hook call.
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
class Arguments extends ArgumentsAbstract implements ArgumentsInterface
{
    /**
     * Valid hooks (complete name).
     * @var array
     */
    private $aHooks = array(
                       'post-commit', 'post-lock', 'post-revprop-change',
                       'post-unlock', 'pre-commit', 'pre-lock',
                       'pre-revprop-change', 'pre-unlock', 'start-commit'
                      );

    /**
     * Available svn actions and their expected parameters.
     * @var array
     */
    private $aActions = array(
                         'start' => array(
                                     'commit' => array(
                                                  'repos', 'user'
                                                 )
                                    ),
                         'pre'   => array(
                                     'commit'         => array(
                                                          'repos', 'txn'
                                                         ),
                                     'lock'           => array(
                                                          'repos', 'user',
                                                          'file'
                                                         ),
                                     'revprop-change' => array(
                                                          'repos', 'rev',
                                                          'user', 'propname',
                                                          'action'
                                                         ),

                                     'unlock'         => array(
                                                          'repos', 'user'
                                                         )
                                    ),

                         'post'  => array(
                                     'commit'         => array(
                                                          'repos', 'rev'
                                                         ),
                                     'lock'           => array(
                                                          'repos', 'user'
                                                         ),
                                     'revprop-change' => array(
                                                          'repos', 'rev',
                                                          'user', 'propname',
                                                          'action'
                                                         ),

                                     'unlock'         => array(
                                                          'repos', 'user'
                                                         )
                                    )
    );

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
     * Return user.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getUser()
    {
        return $this->sUser;
    }

    /**
     * Return Transaction number.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getTransaction()
    {
        return $this->sTxn;
    }

    /**
     * Return version number.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getRevision()
    {
        return $this->iRev;
    }

    /**
     * Return the file on lock call.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getFile()
    {
        return $this->sFile;
    }

    /**
     * Return property name that is required on a Revprop-Change hook call.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getPropertyName()
    {
        return $this->sPropertyName;
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
     * Check the arguments.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function checkArguments()
    {
        if (true === $this->checkMainHook()) {
            if (true === $this->checkArgumentCount()) {
                if (true === $this->checkArgumentTypes()) {
                    $this->bArgumentsOk = true;

                    return;
                }
            }
        }

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
        }

        $sMain = $this->aArguments[($this->iArguments - 1)];

        if (in_array($sMain, $this->aHooks) === false) {
            $this->sError .= 'MainHook ';

            return false;
        }

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
        }

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
            }
        }

        if (0 === $iErrors) {
            return true;
        }

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
        if (true === file_exists($sRepository . '/format')) {
            $this->sRepository     = $sRepository;
            $this->sRepositoryName = basename($sRepository);

            return true;
        }

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
        }

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
        }

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
        }

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
        }

        $this->sError .= 'Action false, not (A, M, D)';

        return false;
    }
}