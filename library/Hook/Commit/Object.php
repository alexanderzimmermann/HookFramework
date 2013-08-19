<?php
/**
 * Class for an object within a commit.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.1.0
 */

namespace Hook\Commit;

use Hook\Commit\Base;

/**
 * Class for an object within a commit.
 * The content of the commited file is not stored in this class.
 * Its only used when a listener needs this data.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 2.1.0
 */
class Object extends Base
{
    /**
     * Object action depending on the VCS adapter (Example: A, D, R, C).
     * @var string
     */
    private $sAction;

    /**
     * Directory or file.
     * @var boolean
     */
    private $bIsDir;

    /**
     * Complete object path.
     * @var string
     */
    private $sObjectPath;

    /**
     * Complete object path.
     * @var string
     */
    private $sRealPath;

    /**
     * File extension (if its a file).
     * @var string
     */
    private $sExtension;

    /**
     * Commit info object.
     * @var Info
     */
    private $oInfo;

    /**
     * Temporary path to object.
     * @var string
     */
    private $sTmpObjectPath;

    /**
     * Actual properties of the object.
     * @var array
     */
    private $aProperties;

    /**
     * Changed properties.
     * @var array
     */
    private $aChangedProperties;

    /**
     * Changed lines.
     * @var array
     */
    private $aChangedParts;

    /**
     * Raw commit lines.
     * @var array
     */
    private $aRawData;

    /**
     * Constructor.
     * @param array $aParams Params for the object.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct(array $aParams)
    {
        parent::__construct($aParams['txn'], $aParams['rev']);

        $this->sAction            = $aParams['action'];
        $this->bIsDir             = $aParams['isdir'];
        $this->sObjectPath        = $aParams['item'];
        $this->sRealPath          = $aParams['real'];
        $this->sExtension         = $aParams['ext'];
        $this->oInfo              = $aParams['info'];
        $this->aChangedProperties = $aParams['props'];
        $this->aChangedParts      = $aParams['lines'];

        // Convert path to a file that will be stored on a temporary place.
        if ($aParams['isdir'] === false) {

            $sPath = str_replace('/', '_', $aParams['item']);
            $sPath = '/tmp/' . $aParams['txn'] . '-' . $sPath;

            $this->sTmpObjectPath = $sPath;
        }
    }

    /**
     * Sets the raw commit lines.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setRawData(array $aRawData)
    {
        $this->aRawData = $aRawData;
    }

    /**
     * Return action.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getAction()
    {
        return $this->sAction;
    }

    /**
     * Return file extension.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getFileExtension()
    {
        return $this->sExtension;
    }

    /**
     * Returns if object is a directory.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function isDir()
    {
        return $this->bIsDir;
    }

    /**
     * Return the complete path of the file in version control system.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getObjectPath()
    {
        return $this->sObjectPath;
    }

    /**
     * Return the real path to file without parts like trunk, branches/1.0.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getRealPath()
    {
        return $this->sRealPath;
    }

    /**
     * Returns the info object of the commit.
     * @return Info
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getInfo()
    {
        return $this->oInfo;
    }

    /**
     * Returns temporary path of file.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getTmpObjectPath()
    {
        return $this->sTmpObjectPath;
    }

    /**
     * Return the actual properties.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getActualProperties()
    {
        return $this->aProperties;
    }

    /**
     * Return the changed properties.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getChangedProperties()
    {
        return $this->aChangedProperties;
    }

    /**
     * Return the changed lines.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getChangedParts()
    {
        return $this->aChangedParts;
    }

    /**
     * Return the raw commit lines.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getRawData()
    {
        return $this->aRawData;
    }
}
