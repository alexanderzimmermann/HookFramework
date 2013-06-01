<?php
/**
 * Data in the transaction.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Commit;

use Hook\Commit\Info;
use Hook\Commit\Object;
use Hook\Filter\Filter;
use Hook\Listener\ListenerInterface;

/**
 * Data in the transaction.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Data
{
    /**
     * Commit Information.
     * @var Info
     */
    private $oInfo;

    /**
     * All directories and file objects. (Multi dimension Array).
     * @var array
     */
    private $aObjects = array();

    /**
     * Available actions depending on adapter.
     * @var array
     */
    private $aAvailableActions;

    /**
     * Constructor.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct(array $aAvailableActions)
    {
        $this->aAvailableActions = $aAvailableActions;

        foreach ($aAvailableActions as $sAction) {

            $this->aObjects[$sAction]['FILES'] = array();
            $this->aObjects[$sAction]['DIRS']  = array();
        }
    }

    /**
     * Sets the info object.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setInfo(Info $oInfo)
    {
        $this->oInfo = $oInfo;
    }

    /**
     * Return the commit info object.
     * @return Info
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getInfo()
    {
        return $this->oInfo;
    }

    /**
     * Adds an object to the matrix.
     * @param \Hook\Commit\Object|Object $oObject The commit item object.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function addObject(Object $oObject)
    {
        if (true === $oObject->isDir()) {

            $this->aObjects[$oObject->getAction()]['DIRS'][] = $oObject;
            return;
        }

        $this->aObjects[$oObject->getAction()]['FILES'][$oObject->getFileExtension()][] = $oObject;
        $this->aObjects[$oObject->getAction()]['FILES']['ALL'][]                        = $oObject;
    }

    /**
     * Return the objects depending on the action.
     * @param mixed $oListener Listener Object.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getObjects($oListener)
    {
        $aRegister    = $oListener->register();
        $aActionTypes = $aRegister['fileaction'];
        $aExtensions  = $aRegister['extensions'];
        $bWithDirs    = (bool) $aRegister['withdirs'];

        $aObjects = array();

        // If both arrays are empty, then there is no data, maybe just property.
        if ((true === empty($aActionTypes)) && (true === empty($aExtensions))) {

            return $aObjects;
        }

        // If one of the arrays is empty, then the other not set to all.
        if ((true === empty($aActionTypes)) && (false === empty($aExtensions))) {

            $aActionTypes = $this->aAvailableActions;
        }

        // If extensions is empty, then we want all files.
        if ((false === empty($aActionTypes)) && (true === empty($aExtensions))) {

            $aExtensions = array('ALL');
        }

        // Search for the requested objects.
        foreach ($aActionTypes as $iIndex => $sAction) {

            if (true === isset($this->aObjects[$sAction])) {

                foreach ($aExtensions as $iIndex => $sExt) {

                    if (true === isset($this->aObjects[$sAction]['FILES'][$sExt])) {

                        $aAddFiles = $this->aObjects[$sAction]['FILES'][$sExt];
                        $aObjects  = array_merge($aObjects, $aAddFiles);
                    }
                }

                // Add directories if required.
                if (true === $bWithDirs) {

                    $aAddDirs = $this->aObjects[$sAction]['DIRS'];
                    $aObjects = array_merge($aObjects, $aAddDirs);
                }
            }
        }

        // List of files empty? Then return empty.
        if (true === empty($aObjects)) {

            return $aObjects;
        }

        // Now recognize the filter of the listener.
        $oFilter  = new Filter($aObjects);
        $aObjects = $oFilter->getFilteredFiles($oListener->getObjectFilter());

        return $aObjects;
    }
}