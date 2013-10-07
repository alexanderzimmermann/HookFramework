<?php
/**
 * * Failure Register Array has wrong value comit.
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

use Hook\Commit\Object;
use Hook\Listener\AbstractObject;

/**
 * Failure Register Array has wrong value comit.
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
class ObjectListenerFailureRegisterValueFalse extends AbstractObject
{
    /**
     * Listener Name.
     * @var string
     */
    protected $sListener = 'Test Object Listener Failure Register Values leer.';

    /**
     * Register the action.
     * @return array
     */
    public function register()
    {
        return array(
            'action'     => 'comit',
            'fileaction' => array('U'),
            'extensions' => array('PHP'),
            'withdirs'   => false
        );
    }

    /**
     * Execute the action.
     * @param Object $oObject Directory / File-Object.
     * @return void
     */
    public function processAction(Object $oObject)
    {
        $oObject->addError('some error');
    }
}
