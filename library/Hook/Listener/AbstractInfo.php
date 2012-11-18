<?php
/**
 * Abstract class for Object Listener.
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

use Hook\Listener\InfoInterface;

/**
 * Abstract class for Object Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Info
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 2.1.0
 */
abstract class AbstractInfo implements ListenerInterface, InfoInterface
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Default Listener Name';

	/**
	 * Configuration array.
	 * @var array
	 */
	protected $aCfg = array();

	/**
	 * Returns the listener name for identification.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getListenerName()
	{
		return $this->sListener;
	} // function

	/**
	 * Set the configuration for the listener.
	 * @param array $aCfg Configuration array for this listener.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setConfiguration(array $aCfg)
	{
		$this->aCfg = $aCfg;
	} // function
} // class
