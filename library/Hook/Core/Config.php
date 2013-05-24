<?php
/**
 * Configuration object for repositories.
 * @category   Core
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.1.0
 */

namespace Hook\Core;

/**
 * Configuration object for repositories.
 * @category   Core
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
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
    } // function

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
            } // if

            $this->divideValues($aNew, $aTmp, $mSection);
        } // foreach

        $this->aCfg = $aNew;
    } // function

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
                $aNew[$aSection[0]][$aSection[1]][$sIdentifier] = $aValues;
                continue;
            } // if

            $aTmp = explode('.', $sIdentifier);

            if (false === isset($aNew[$aSection[0]][$aSection[1]][$aTmp[0]])) {
                $aNew[$aSection[0]][$aSection[1]][$aTmp[0]] = array();
            } // if

            $aNew[$aSection[0]][$aSection[1]][$aTmp[0]][$aTmp[1]] = $aValues;
        } // foreach
    } // function

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
            } // if
        } // if

        return false;
    } // function
} // class
