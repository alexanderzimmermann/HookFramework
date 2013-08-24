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

namespace Hook\Adapter;

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
abstract class ArgumentsAbstract
{
    /**
     * Delimiter.
     * @var string
     */
    protected $sDelimiter = '-';

    /**
     * All available hooks for validation.
     * @var array
     */
    protected $aHooks = array();

    /**
     * All available sub actions for validation.
     * @var array
     */
    protected $aActions = array();

    /**
     * Arguments of hook call.
     * @var array
     */
    protected $aArguments;

    /**
     * arguments count.
     * @var integer
     */
    protected $iArguments;

    /**
     * Arguments Ok.
     * @var boolean
     */
    protected $bArgumentsOk;

    /**
     * main type for hook call (start, pre, post).
     * @var string
     */
    protected $sMainType;

    /**
     * Subtype for Hook (commit, lock).
     * @var string
     */
    protected $sSubType;

    /**
     * User.
     * @var string
     */
    protected $sUser;

    /**
     * Repository path.
     * @var string
     */
    protected $sRepository;

    /**
     * Repository name.
     * @var string
     */
    protected $sRepositoryName;

    /**
     * Transaction number.
     * @var string
     */
    protected $sTxn;

    /**
     * Internal error text.
     * @var string
     */
    protected $sError;

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
    protected function checkMainHook()
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

        if (false === strpos($sMain, $this->sDelimiter)) {
            $this->sError .= ' MainHook is wrong: ' . $sMain;
            return false;
        }

        $aHook           = explode($this->sDelimiter, $sMain, 2);
        $this->sMainType = $aHook[0];
        $this->sSubType  = $aHook[1];

        if (true === isset($aHook[2])) {
            $this->sSubType .= $this->sDelimiter . $aHook[2];
        }

        // Remove MainHook Argument.
        array_pop($this->aArguments);
        $this->iArguments--;

        return true;
    }

    /**
     * Compare count of arguments.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function checkArgumentCount()
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
    protected function checkArgumentTypes()
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
     * Get the available hooks.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getAvailableHooks()
    {
        return $this->aHooks;
    }

    /**
     * Return complete hook type.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getMainHook()
    {
        return $this->sMainType . $this->sDelimiter . $this->sSubType;
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
     * Return Transaction number.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getTransaction()
    {
        return $this->sTxn;
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
}
