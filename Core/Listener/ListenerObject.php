<?php
/**
 * Interface für Object Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Object
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Interface für Object Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Object
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Interface available since Release 1.0.0
 */
interface ListenerObject
{
	/**
	 * Listener Name zurueck geben.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getListenerName();

	/**
	 * Registrieren auf die Aktion und Dateien (Extension).
	 *
	 * Beispiel
	 * <pre>
	 * return array(
	 * 		   'action'     => 'commit',
	 * 		   'fileaction' => array(
	 * 							'A', 'U'
	 * 						   ),
	 * 		   'extensions' => array(
	 * 							'PHP'
	 * 						   )
	 * 		  );
	 * </pre>
	 *
	 * Werte fuer <i>action:</i>
	 * <ul>
	 * <li><b>Bei post</b></li>
	 * <li>commit</li>
	 * <li>lock</li>
	 * <li>revprop-change</li>
	 * <li>unlock</li>
	 * <li><b>Bei pre</b></li>
	 * <li>commit</li>
	 * <li>lock</li>
	 * <li>revprop-change</li>
	 * <li>unlock</li>
	 * <li><b>Bei start</b></li>
	 * <li>start-commit</li>
	 * </ul>
	 *
	 * Werte fuer <i>fileaction:</i>
	 * <ul
	 * <li>A Hinzugefuegt</li>
	 * <li>U Aktualisierte</li>
	 * <li>D Geloescht</li>
	 * </ul>
	 *
	 * Werte fuer <i>extensions:</i>
	 * PHP, C, TXT, CSS, usw.
	 *
	 * Werte fuer
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register();

	/**
	 * Ausfuehren der Aktion.
	 * @param CommitObject $oObject Verz. / Datei-Objekt.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitObject $oObject);
} // interface
