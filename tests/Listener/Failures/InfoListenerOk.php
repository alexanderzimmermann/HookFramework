<?php
/**
 * Listener Info Ok.
 * @category   Hook
 * @package    Listener
 * @subpackage Failures
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Listener Info Ok.
 * @category   Hook
 * @package    Listener
 * @subpackage Failures
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class InfoListenerOk extends ListenerInfoAbstract
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Test Info Listener Ok.';

	/**
	 * Registrieren auf die Aktion.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
		return 'commit';
	} // function

	/**
	 * Ausfuehren der Aktion.
	 * @param CommitInfo $oInfo Info des Commits.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitInfo $oInfo)
	{
	} // function
} // class
