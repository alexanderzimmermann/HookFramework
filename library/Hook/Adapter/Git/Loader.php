<?php
/**
 * Loading the different listener types.
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

use Hook\Adapter\LoaderAbstract;
use Hook\Listener\AbstractInfo;

/**
 * Loading the different listener types.
 * There are 2 types of main hooks.
 * One for Start (start), before a transaction is started.
 * One after the transaction is stared, but not commit to the repository (pre).
 * One after the transaction is commit to the repository and the work is done (post).
 * <ul>
 * <li>Client</li>
 * <li>Server</li>
 * </ul>
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
class Loader extends LoaderAbstract
{
    /**
     * Init the Listener Parser.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function init()
    {
        $this->readDirectory();
        $this->checkListener();
        $this->registerListenerInfo();
        $this->registerListenerObject();
    }

    /**
     * Register values for info listener and check it.
     * @param AbstractInfo $oListener Name of listener objects.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function registerListenersInfo(AbstractInfo $oListener)
    {
        if (true === parent::registerListenersInfo($oListener)) {
            if ($oListener->register() !== $this->oArguments->getSubType()) {
                return false;
            }
            return true;
        }
        return false;
    }
}
