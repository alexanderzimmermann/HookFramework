<?php
/**
 * Loading the different listener types.
 * @category   Core
 * @package    Listener
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter\Svn;

use Hook\Adapter\LoaderAbstract;

/**
 * Loading the different listener types.
 * There are 3 types of main hooks.
 * One for Start (start), before a transaction is started.
 * One after the transaction is stared, but not commit to the repository (pre).
 * One after the transaction is commit to the repository and the work is done (post).
 * <ul>
 * <li>Start</li>
 * <li>Pre</li>
 * <li>Post</li>
 * </ul>
 * @category   Core
 * @package    Listener
 * @subpackage Main
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
}
