<?php
/**
 * Class for parsing the change properties of a commit.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once 'Core/Commit/Diff/Property.php';

/**
 * Class for parsing the change properties of a commit.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Properties
{
	/**
	 * Difference Properties.
	 * @var array
	 */
	private $aProperties = array();

	/**
	 * Constructor.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct()
	{
	} // function

	/**
	 * Parsing the properties lines.
	 * @param integer $iId         Represents the index in the file commit stack.
	 * @param array   $aProperties The extracted properties lines from the diff.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function parse($iId, array $aProperties)
	{
		if (true === empty($aProperties))
		{
			return;
		} // if

		// Iterate over the lines if there could be more properties.
		$sValue    = '';
		$bSwitch   = null;
		$oProperty = null;

		foreach ($aProperties as $iLine => $sLine)
		{
			if ((substr($sLine, 0, 5) === 'Name:') || (substr($sLine, 0, 9) === 'Modified:'))
			{
				$iPos = 6;
				if (false !== strpos($sLine, 'Modified'))
				{
					$iPos = 10;
				} // if

				if (null !== $oProperty)
				{
					$this->setValue($oProperty, $bSwitch, $sValue);
				} // if

				$sProperty = substr($sLine, $iPos, (strlen($sLine) - $iPos));
				$oProperty = new Diff_Property($sProperty);
				$bSwitch   = null;

				$this->aProperties[$iId][$sProperty] = $oProperty;
			}
			else if (substr($sLine, 0, 5) === '   - ')
			{
				$this->setValue($oProperty, $bSwitch, $sValue);

				$sValue  = str_replace('   - ', '', $sLine) . "\n";
				$bSwitch = false;
			}
			else if (substr($sLine, 0, 5) === '   + ')
			{
				$this->setValue($oProperty, $bSwitch, $sValue);

				$sValue  = str_replace('   + ', '', $sLine) . "\n";
				$bSwitch = true;
			}
			else
			{
				$sValue .= $sLine . "\n";
			} // if
		} // foreach

		// Handle the last Value.
		$this->setValue($oProperty, $bSwitch, $sValue);
	} // function

	/**
	 * Set the collected value.
	 * @param Diff_Property $oProperty Diff property object.
	 * @param boolean       $bSwitch   New value oder old value switch.
	 * @param string        $sValue    The value for the property.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function setValue(Diff_Property $oProperty, $bSwitch, $sValue)
	{
		// New value.
		if ($bSwitch === true)
		{
			$oProperty->setNewValue(trim($sValue));
		} // if

		// Old value.
		if ($bSwitch === false)
		{
			$oProperty->setOldValue($sValue);
		} // if
	} // function

	/**
	 * Return the difference properties.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getProperties()
	{
		return $this->aProperties;
	} // function
} // class