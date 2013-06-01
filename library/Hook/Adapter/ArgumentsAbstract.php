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
abstract class ArgumentsAbstract
{

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
     * Returns error text.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getError()
    {
        return $this->sError;
    }
}
