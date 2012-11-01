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
 * @since      File available since Release 1.0.0
 */

namespace Hook\Listener;

/**
 * Abstract class for Object Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Info
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
abstract class InfoAbstract implements Info
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Default Listener Name';

	/**
	 * Listenern Name zurueck liefern.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getListenerName()
	{
		return $this->sListener;
	} // function
} // class
