<?php
/**
 * Helper class for tests of the data object.
 * @category   Test
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Core\Commit;

use Hook\Commit\Data\Object;
use Hook\Listener\AbstractObject;

/**
 * Helper class for tests of the data object.
 * @category   Test
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class DataHelperActionTypes extends AbstractObject
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Test Listener';

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
				'extensions' => array('ALL'),
				'withdirs'   => true
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
