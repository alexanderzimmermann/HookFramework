<?php
/**
 * Listener Info Ok.
 * @category   Hook
 * @package    Listener
 * @subpackage Failures
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Listener\Failures;

use Hook\Commit\Info;
use Hook\Listener\AbstractInfo;

/**
 * Listener Info Ok.
 * @category   Hook
 * @package    Listener
 * @subpackage Failures
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class InfoListenerOk extends AbstractInfo
{
    /**
     * Listener Name.
     * @var string
     */
    protected $sListener = 'Test Info Listener Ok.';

    /**
     * Register the action.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function register()
    {
        return 'commit';
    }

    /**
     * Process action.
     * @param Info $oInfo Info des Commits.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function processAction(Info $oInfo)
    {
        $oInfo = null;
    }
}
