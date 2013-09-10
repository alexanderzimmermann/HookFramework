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
use Hook\Adapter\ArgumentsAbstract;

/**
 * Class for handling the arguments of a hook call.
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
class Arguments extends ArgumentsAbstract implements ArgumentsInterface
{
    /**
     * Valid hooks (complete name).
     * @var array
     */
    protected $aHooks = array(
                         'post-commit', 'post-lock', 'post-revprop-change',
                         'post-unlock', 'pre-commit', 'pre-lock',
                         'pre-revprop-change', 'pre-unlock', 'start-commit'
                      );

    /**
     * Available svn actions and their expected parameters.
     * @var array
     */
    protected $aActions = array(
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
                                                            'repos', 'txn',
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

        $this->sError .= 'User missing or wrong format.';

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

        $this->sError .= 'Transaction missing or wrong format.';

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

        $this->sError .= 'Revision missing wrong format.';

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