<?php
/**
 * Failure Register Werte vom falschen Typ.
 * @category   Hook
 * @package    Listener
 * @subpackage Failures
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Listener\Failures;

use Hook\Commit\Data\Object;
use Hook\Listener\AbstractObject;

/**
 * Failure Register Werte vom falschen Typ.
 * @category   Hook
 * @package    Listener
 * @subpackage Failures
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class ObjectListenerFailureRegisterValues extends AbstractObject
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Test Object Listener Failure Register Values leer.';

	/**
	 * Register the action.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
		return array(
				'action'     => 'commit',
				'fileaction' => 'U',
				'extensions' => array(),
				'withdirs'   => false
			   );
	} // function

	/**
	 * Execute the action.
	 * @param Object $oObject Directory / File-Object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(Object $oObject)
	{
	} // function
} // class
