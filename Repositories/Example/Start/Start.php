<?php
/**
 * Start Listener.
 * @category   Hook
 * @package    Listener
 * @subpackage Start
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Example\Start;

use Hook\Commit\Data\Info;
use Hook\Listener\AbstractInfo;
/**
 * Start Listener.
 * @category   Hook
 * @package    Listener
 * @subpackage Start
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Start extends AbstractInfo
{
	/**
	 * Register the action.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
		return 'commit';
	} // function

	/**
	 * Execute the action.
	 * @param Info $oInfo Info des Commits.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(Info $oInfo)
	{
	} // function
} // class
