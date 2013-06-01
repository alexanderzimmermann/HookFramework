<?php
/**
 * Configuration object.
 * @category   Core
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.1.0
 */

namespace Hook\Core;

/**
 * Configuration object.
 * @category   Core
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 2.1.0
 */
class Config
{
    /**
     * Configuration Array.
     * @var array
     */
    protected $aCfg = array();

    /**
     * Load the configuration file.
     * @param string $sFile Configuration file name.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function loadConfigFile($sFile)
    {
        if (false === file_exists($sFile)) {

            return false;
        } /// if

        $this->aCfg = parse_ini_file($sFile, true);

        $this->divideSections();

        return true;
    }

    /**
     * Divide sections to main hook and listener name.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function divideSections()
    {
        $aNew = array();
        foreach ($this->aCfg as $sSection => $mSection) {

            $aTmp = explode(':', $sSection);

            if (false === isset($aNew[$aTmp[0]])) {
                $aNew[$aTmp[0]] = array();
            }

            $this->divideValues($aNew, $aTmp, $mSection);
        }

        $this->aCfg = $aNew;
    }

    /**
     * Divide the value lines.
     * @param array &$aNew    The new configuration array.
     * @param array $aSection The divided section identifier.
     * @param mixed $mSection Values of section.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function divideValues(array &$aNew, array $aSection, $mSection)
    {
        // Analyze value.
        foreach ($mSection as $sIdentifier => $aValues) {

            // No Point, leave as is and go to next.
            if (false === strpos($sIdentifier, '.')) {

                if (true === isset($aSection[1])) {

                    $aNew[$aSection[0]][$aSection[1]][$sIdentifier] = $aValues;
                } else {

                    $aNew[$aSection[0]][$sIdentifier] = $aValues;
                }

                continue;
            }

            $aTmp = explode('.', $sIdentifier);

            if (false === isset($aNew[$aSection[0]][$aSection[1]][$aTmp[0]])) {
                $aNew[$aSection[0]][$aSection[1]][$aTmp[0]] = array();
            }

            $aNew[$aSection[0]][$aSection[1]][$aTmp[0]][$aTmp[1]] = $aValues;
        }
    }

    /**
     * Get the data for the listener.
     * @param string $sMain     Main hook identifier.
     * @param string $sListener Listener class name.
     * @return string|array|boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getConfiguration($sMain, $sListener)
    {
        if (true === isset($this->aCfg[$sMain])) {
            if (true === isset($this->aCfg[$sMain][$sListener])) {
                return $this->aCfg[$sMain][$sListener];
            }
        }

        return false;
    }
}
