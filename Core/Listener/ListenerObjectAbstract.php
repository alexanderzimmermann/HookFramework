<?php
/**
 * Abstrakte Klasse fuer Object Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Object
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once 'Core/Listener/ListenerObject.php';
require_once 'Core/Filter/ObjectFilter.php';

/**
 * Abstrakte Klasse fuer Object Listener.
 * @category   Core
 * @package    Listener
 * @subpackage Object
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
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
	 * Konstruktor.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct()
	{
		$this->oObjectFilter = new ObjectFilter();
	} // function

	/**
	 * Listenern Name zurueck liefern.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getListenerName()
	{
		return $this->sListener;
	} // function

	/**
	 * Filterobjekt des Listeners zurück geben.
	 * @return ObjectFilter
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getObjectFilter()
	{
		return $this->oObjectFilter;
	} // function
} // class
