<?php
/**
 * Interface for Object Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Object
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.1.0
 */

namespace Hook\Listener;

use Hook\Commit\Data\Object;

/**
 * Interface for Object Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Object
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Interface available since Release 2.1.0
 */
interface ObjectInterface
{
	/**
	 * Execute the action.
	 * @param Object $oObject Commited object.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(Object $oObject);
} // interface
