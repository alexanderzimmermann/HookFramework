<?php
/**
 * Failure Register values with wrong type 2.
 * @category   Hook
 * @package    Listener
 * @subpackage Failures
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: ObjectListenerFailureRegisterValues2.php 134 2009-01-02 22:17:12Z alexander $
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Listener\Failures\Pre;

use Hook\Commit\Object;
use Hook\Listener\AbstractObject;

/**
 * Failure Register values with wrong type 2.
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
class ObjectListenerFailureRegisterValues2 extends AbstractObject
{
    /**
     * Listener Name.
     * @var string
     */
    protected $sListener = 'Test Object Listener Failure Register Values empty.';

    /**
     * Register the action.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function register()
    {
        return array(
            'action'     => 'commit',
            'fileaction' => array(),
            'extensions' => 'PHP',
            'withdirs'   => false
        );
    }

    /**
     * Execute the action.
     * @param Object $oObject Directory / File-Object.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function processAction(Object $oObject)
    {
        $oObject->addError('Error');
    }
}
