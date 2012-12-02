<?php
/**
 * Property class for the commit objects.
 * @category   Core
 * @package    Commit
 * @subpackage Diff
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Commit\Data\Diff;

/**
 * Property class for the commit objects.
 * @category   Core
 * @package    Commit
 * @subpackage Diff
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Property
{
	/**
	 * Property name value.
	 * @var string
	 */
	private $sProperty;

	/**
	 * Old value.
	 * @var string
	 */
	private $sOldValue;

	/**
	 * New value.
	 * @var string
	 */
	private $sNewValue;

	/**
	 * Constructor.
	 * @param string $sProperty Name of property.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct($sProperty)
	{
		$this->sProperty = $sProperty;
	} // function

	/**
	 * Return the property name.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getPropertyName()
	{
		return $this->sProperty;
	} // function

	/**
	 * Set the value.
	 * @param string $sNewValue Value.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setNewValue($sNewValue)
	{
		$this->sNewValue = $sNewValue;
	} // function

	/**
	 * Set the old value.
	 * @param string $sOldValue Old value.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setOldValue($sOldValue)
	{
		$this->sOldValue = $sOldValue;
	} // function

	/**
	 * Return the new value.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getNewValue()
	{
		return $this->sNewValue;
	} // function

	/**
	 * Return old value.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getOldValue()
	{
		return $this->sOldValue;
	} // function
} // class