<?php
/**
 * Failure Type Value Test Listener.
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

namespace HookTest\Listener\Mixed\All;

use Hook\Commit\Info;
use Hook\Listener\AbstractInfo;

/**
 * Failure Type Value Test Listener.
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
class InfoListenerFailureTypeValueFalse extends AbstractInfo
{
    /**
     * Listener Name.
     * @var string
     */
    protected $sListener = 'Test listener with no valid register value.';

    /**
     * Register the action.
     * @return string
     */
    public function register()
    {
        // commit.
        return 'comit';
    }

    /**
     * Execute the action.
     * @param Info $oInfo Info des Commits.
     * @return void
     */
    public function processAction(Info $oInfo)
    {
        $oInfo->addError('some error');

    }
}