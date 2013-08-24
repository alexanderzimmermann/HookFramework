<?php
/**
 * Class for parsing the change properties of a commit.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter\Svn\Parser;

use Hook\Commit\Diff\Property;

/**
 * Class for parsing the change properties of a commit.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
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
    }

    /**
     * Parsing the properties lines.
     * @param integer $iId         Represents the index in the file commit stack.
     * @param array   $aProperties The extracted properties lines from the diff.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parse($iId, array $aProperties)
    {
        if (true === empty($aProperties)) {
            return;
        }

        // Iterate over the lines if there could be more properties.
        $sValue    = '';
        $bSwitch   = null;
        $oProperty = null;
        $aMatch    = array();

        foreach ($aProperties as $sLine) {

            if (preg_match('/(Name:|Modified:|Added:|Deleted:)/', $sLine, $aMatch) > 0) {

                // Colon position plus 2 for one space sign.
                $iPos = (strpos($sLine, ':') + 2);

                if (null !== $oProperty) {
                    $this->setValue($oProperty, $bSwitch, $sValue);
                }

                $sProperty = substr($sLine, $iPos, (strlen($sLine) - $iPos));
                $oProperty = new Property($sProperty);
                $bSwitch   = null;

                $this->aProperties[$iId][$sProperty] = $oProperty;
            } else if (substr($sLine, 0, 5) === '   - ') {
                $this->setValue($oProperty, $bSwitch, $sValue);

                $sValue  = str_replace('   - ', '', $sLine) . "\n";
                $bSwitch = false;
            } else if (substr($sLine, 0, 5) === '   + ') {
                $this->setValue($oProperty, $bSwitch, $sValue);

                $sValue  = str_replace('   + ', '', $sLine) . "\n";
                $bSwitch = true;
            } else {
                $sValue .= $sLine . "\n";
            }
        }

        // Handle the last Value.
        $this->setValue($oProperty, $bSwitch, $sValue);
    }

    /**
     * Set the collected value.
     * @param Property $oProperty Diff property object.
     * @param boolean  $bSwitch   New value oder old value switch.
     * @param string   $sValue    The value for the property.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function setValue(Property $oProperty, $bSwitch, $sValue)
    {
        // New value.
        if ($bSwitch === true) {
            $oProperty->setNewValue(trim($sValue));
        }

        // Old value.
        if ($bSwitch === false) {
            $oProperty->setOldValue($sValue);
        }
    }

    /**
     * Return the difference properties.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getProperties()
    {
        return $this->aProperties;
    }
}