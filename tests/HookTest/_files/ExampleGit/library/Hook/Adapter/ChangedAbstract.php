<?php
/**
 * Comment.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter;

use \ArrayObject;

/**
 * Comment.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
abstract class ChangedAbstract
{
    /**
     * Items of the commit, but just the file with path.
     * @var array
     */
    protected $aItems = array();

    /**
     * The created commit objects.
     * @var ArrayObject
     */
    protected $oItems;

    /**
     * Constructor.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct()
    {
        $this->oItems = new ArrayObject();
    }

    /**
     * Return the create commit objects.
     * @return ArrayObject
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getObjects()
    {
        return $this->oItems;
    }

    /**
     * Return items as array.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getObjectsArray()
    {
        return $this->oItems->getArrayCopy();
    }

    /**
     * Return file items.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getFiles()
    {
        return $this->aItems;
    }

    /**
     * Adds the parsed changed item to the lists.
     * @param array $aValues The parsed values of the changed item list.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function addItem(array $aValues)
    {
        $this->aItems[] = $aValues['item'];

        $this->oItems->append($aValues);
    }

    /**
     * Determine file extensions.
     * @param string $sFile File to determine extension for.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function determineFileExtension($sFile)
    {
        // Search first point, starting from end of filename.
        // If no point is found, its a file without extension.
        $iPos       = strrpos($sFile, '.');
        $sExtension = '';

        if ($iPos !== false) {

            $iPos++;
            $sExtension = substr($sFile, $iPos, (strlen($sFile) - $iPos));
        }

        return strtoupper($sExtension);
    }
}
