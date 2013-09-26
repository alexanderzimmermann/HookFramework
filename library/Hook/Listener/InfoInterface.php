<?php
/**
 * Interface für Info Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Info
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.1.0
 */

namespace Hook\Listener;

use Hook\Commit\Info;

/**
 * Interface für Info Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Info
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Interface available since Release 2.1.0
 */
interface InfoInterface
{
    /**
     * Execute the action.
     * @param Info $oInfo Info des Commits.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function processAction(Info $oInfo);
}
