<?php
/**
 * Abstract Class for object listener.
 * @category   Core
 * @package    Listener
 * @subpackage Object
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Core\Listener;

use Core\Filter\ObjectFilter;
use Core\Listener\ListenerObject;

/**
 * Abstract Class for object listener.
 * @category   Core
 * @package    Listener
 * @subpackage Object
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
abstract class ListenerObjectAbstract implements ListenerObject
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Default Listener Name';

	/**
	 * Object Filter.
	 * @var ObjectFilter
	 */
	protected $oObjectFilter;

	/**
	 * Constructor.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct()
	{
		$this->oObjectFilter = new ObjectFilter();
	} // function

	/**
	 * Return listener name.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getListenerName()
	{
		return $this->sListener;
	} // function

	/**
	 * Return Filter object of the listeners.
	 * @return ObjectFilter
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getObjectFilter()
	{
		return $this->oObjectFilter;
	} // function
} // class
