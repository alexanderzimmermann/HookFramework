<?php
/**
 * Difference in Transaction / Revision.
 * @category   Core
 * @package    Commit
 * @subpackage Diff
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Commit\Diff;

use Hook\Commit\Diff\Diff;

/**
 * Difference in Transaction / Revision.
 * @category   Core
 * @package    Commit
 * @subpackage Diff
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Lines
{
    /**
     * The information (i.e. @@ -32,5 +34,19 @@).
     * @var Diff
     */
    protected $oInfo;

    /**
     * Raw lines from svn diff for this part.
     * @var array
     */
    protected $aLines;

    /**
     * The old lines.
     * @var array
     */
    protected $aOldLines = array();

    /**
     * The new lines.
     * @var array
     */
    protected $aNewLines = array();

    /**
     * Constructor.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct()
    {
    }

    /**
     * Set the change information.
     * @param Diff The Unified diff information.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setInfo(Diff $oInfo)
    {
        $this->oInfo = $oInfo;
    }

    /**
     * Get the change information
     * @return Diff
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getInfo()
    {
        return $this->oInfo;
    }

    /**
     * Set the raw lines coming from diff
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setRawLines(array $aLines)
    {
        $this->aLines = $aLines;
    }

    /**
     * Return the changed lines.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getRawLines()
    {
        return $this->aLines;
    }

    /**
     * Sets the lines that were removed in that commit for that file.
     * @param array $aOldLines The removed lines.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setOldLines(array $aOldLines)
    {
        $this->aOldLines = $aOldLines;
    }

    /**
     * Set the lines that were added in that commit for that file.
     * @param array $aNewLines The new lines.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setNewLines(array $aNewLines)
    {
        $this->aNewLines = $aNewLines;
    }

    /**
     * Get the removed lines.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getOldLines()
    {
        return $this->aOldLines;
    }

    /**
     * Get the added lines.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getNewLines()
    {
        return $this->aNewLines;
    }
}