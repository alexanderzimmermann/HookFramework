<?php
/**
 * Diff information for the commit.
 * @category   Core
 * @package    Commit
 * @subpackage Diff
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.1.1
 */

namespace Hook\Commit\Diff;

/**
 * Diff information for the commit.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 2.1.1
 * @see        http://en.wikipedia.org/wiki/Diff#Unified_format
 */
class Diff
{
    /**
     * Start line old file.
     * @var integer
     */
    protected $iOldStart = 0;

    /**
     * Length of lines in old file.
     * @var integer
     */
    protected $iOldLength = 0;

    /**
     * Start line in new file.
     * @var integer
     */
    protected $iNewStart = 0;

    /**
     * Length of lines in new file.
     * @var integer
     */
    protected $iNewLength = 0;

    /**
     * Set the old start.
     * @param integer $iOldStart Old start line number for the change area.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setOldStart($iOldStart)
    {
        $this->iOldStart = $iOldStart;
    }

    /**
     * Set the old length of change area.
     * @param integer $iOldLength Old change area length.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setOldLength($iOldLength)
    {
        $this->iOldLength = $iOldLength;
    }

    /**
     * Sets the new start line number.
     * @param integer $iNewStart New start line number for this change area.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setNewStart($iNewStart)
    {
        $this->iNewStart = $iNewStart;
    }

    /**
     * Sets the line length of the new change area.
     * @param integer $iNewLength Lines that were added
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setNewLength($iNewLength)
    {
        $this->iNewLength = $iNewLength;
    }

    /**
     * Get the old start.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getOldStart()
    {
        return $this->iOldStart;
    }

    /**
     * Get the old length of change area.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getOldLength()
    {
        return $this->iOldLength;
    }

    /**
     * Gets the new start line number.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getNewStart()
    {
        return $this->iNewStart;
    }

    /**
     * Gets the line length of the new change area.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getNewLength()
    {
        return $this->iNewLength;
    }
}
