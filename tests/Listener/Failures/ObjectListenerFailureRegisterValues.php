<?php
/**
 * Failure Register Werte vom falschen Typ.
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
 * Failure Register Werte vom falschen Typ.
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
class ObjectListenerFailureRegisterValues extends ListenerObjectAbstract
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Test Object Listener Failure Register Values leer.';

	/**
	 * Registrieren auf die Aktion.
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
	 * Ausfuehren der Aktion.
	 * @param CommitObject $oObject Verz. / Datei-Objekt.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitObject $oObject)
	{
	} // function
} // class
