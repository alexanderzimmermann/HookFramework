<?php
/**
 * Interface für Info Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Info
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.1.0
 */

namespace Hook\Listener;

use Hook\Commit\Data\Info;

/**
 * Interface für Info Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Info
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Interface available since Release 2.1.0
 */
interface InfoInterface
{
	/**
	 * Return listener name.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getListenerName();

	/**
	 * Register the action and the file actions and file types that are needed.
	 *
	 * Example
	 * <pre>
	 * return 'commit';
	 * </pre>
	 *
	 * Values for <i>action:</i>
	 * <ul>
	 * <li><b>On post</b></li>
	 * <li>commit</li>
	 * <li>lock</li>
	 * <li>revprop-change</li>
	 * <li>unlock</li>
	 * <li><b>On pre</b></li>
	 * <li>commit</li>
	 * <li>lock</li>
	 * <li>revprop-change</li>
	 * <li>unlock</li>
	 * <li><b>On start</b></li>
	 * <li>start-commit</li>
	 * </ul>
	 *
	 * Values for <i>fileaction:</i>
	 * <ul
	 * <li>A Added</li>
	 * <li>U Updated</li>
	 * <li>D Deleted</li>
	 * </ul>
	 *
	 * Values for <i>extensions:</i>
	 * PHP, C, TXT, CSS, usw.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register();

	/**
	 * Execute the action.
	 * @param Info $oInfo Info des Commits.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(Info $oInfo);
} // interface
