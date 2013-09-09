<?php
/**
 * Global interface for all listener.
 * @category   Main
 * @package    Hook
 * @subpackage Listener
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Listener;

/**
 * Global interface for all listener.
 * @category   Main
 * @package    Hook
 * @subpackage Listener
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
interface ListenerInterface
{
    /**
     * Return listener name.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getListenerName();

    /**
     * Register the action and the file actions and file types that are needed.
     * Example for info listener type.
     * <pre>
     * return 'commit';
     * </pre>
     * Example for object listener type.
     * <pre>
     * return array(
     *           'action'     => 'commit',
     *           'fileaction' => array(
     *                            'A', 'U'
     *                           ),
     *           'extensions' => array(
     *                            'PHP'
     *                           )
     *          );
     * </pre>
     * Values for <i>action:</i> depend on the used vcs.
     * Values for <i>extensions:</i>
     * PHP, C, TXT, CSS, usw.
     * @return string|array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function register();

    /**
     * Set the configuration for the listener.
     * @param array $aCfg Configuration array for this listener.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setConfiguration(array $aCfg);
}
