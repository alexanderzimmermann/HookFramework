<?php
/**
 * Class for parsing the difference lines of a commit.
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

use Hook\Commit\Diff\Lines as DiffLines;
use Hook\Commit\Diff\Diff;

/**
 * Class for parsing the difference lines of a commit.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Lines
{
    /**
     * Difference Lines.
     * @var array
     */
    private $aLines = array();

    /**
     * Actual Diff Lines object..
     * @var DiffLines
     */
    private $oActualLines = null;

    /**
     * Raw lines container.
     * @var array
     */
    private $aRaw;

    /**
     * New lines container.
     * @var array
     */
    private $aNew;

    /**
     * Old information intro line.
     * @var string
     */
    private $sOld;

    /**
     * Old lines container .
     * @var array
     */
    private $aOld;

    /**
     * New information intro line..
     * @var string
     */
    private $sNew;

    /**
     * Constructor.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct()
    {
        $this->resetContainers();
    }

    /**
     * Parse the lines.
     * @param array $aLines The extracted properties lines from the diff.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parse(array $aLines)
    {
        // Old stuff head: --- path/file   2012-11-18 21:16:33 UTC (rev 10).
        $this->sOld = array_shift($aLines);

        // New stuff head: +++ path/file   2012-11-20 20:23:01 UTC (txn 10-k).
        $this->sNew = array_shift($aLines);

        // No lines, no parse, Binary diff, no parse.
        if ((true === empty($aLines)) || (false !== strpos($this->sOld, 'Binary'))) {
            return;
        }

        // Reset objects.
        $this->aLines = array();

        $this->splitChangedSegments($aLines);
    }

    /**
     * Reset Containers
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function resetContainers()
    {
        $this->aRaw = array();
        $this->aNew = array();
        $this->aOld = array();
    }

    /**
     * Adds a new DiffLines area to the list.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function addNewArea()
    {
        $this->oActualLines->setRawLines($this->aRaw);
        $this->oActualLines->setNewLines($this->aNew);
        $this->oActualLines->setOldLines($this->aOld);

        $this->aLines[] = $this->oActualLines;
    }

    /**
     * Separate the @@ -n,n +n,n @@ segments
     * @param array $aLines The diff lines into its change parts.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function splitChangedSegments(array $aLines)
    {
        $iNew = 0;
        $iOld = 0;
        foreach ($aLines as $sLine) {
            // Lines @@ -32,5 +34,19 @@
            if ('@@' === substr($sLine, 0, 2)) {
                // $this->aLines[$this->iId][] = $oDiffLines;
                if (null !== $this->oActualLines) {
                    $this->addNewArea();
                }

                $oDiffInfo          = $this->splitChangeInfo($sLine);
                $this->oActualLines = new DiffLines();
                $this->oActualLines->setInfo($oDiffInfo);

                // New changed item.
                $this->resetContainers();
                $iNew = $oDiffInfo->getNewStart();
                $iOld = $oDiffInfo->getOldStart();

                // This line will be now skipped.
                continue;
            }

            $this->aRaw[] = $sLine;

            // If the line is empty, continue.
            if ('' === $sLine) {
                $iNew++;
                $iOld++;
                continue;
            }

            if (('+' !== $sLine[0]) && ('-' !== $sLine[0])) {
                $iNew++;
                $iOld++;
            }

            // Added lines with +.
            if ('+' === $sLine[0]) {
                $this->aNew[$iNew] = substr($sLine, 1, (strlen($sLine) - 1));
                $iNew++;
            }

            // Deleted lines with -.
            if ('-' === $sLine[0]) {
                $this->aOld[$iOld] = substr($sLine, 1, (strlen($sLine) - 1));
                $iOld++;
            }
        }

        $this->addNewArea();
    }

    /**
     * Split change info.
     * @param string $sInfo Info line.
     * @return Diff
     * @author Alexander Zimmermann <alex@azimmermann.com>
     * @see    http://en.wikipedia.org/wiki/Diff#Unified_format
     */
    protected function splitChangeInfo($sInfo)
    {

        /**
        Each hunk range is of the format l,s where l is the starting line number and s is the
        number of lines the change hunk applies to for each respective file.
        In many versions of GNU diff, each range can omit the comma and trailing value s, in
        which case s defaults to 1. Note that the only really interesting value is the l line
        number of the first range; all the other values can be computed from the diff.
         */

        $oUnified = new Diff();

        $sInfo = trim(str_replace('@', '', $sInfo));

        $aInfo = explode(' ', $sInfo);
        $aOld  = explode(',', $aInfo[0]);
        $aNew  = explode(',', $aInfo[1]);

        $oUnified->setOldStart((int)str_replace('-', '', $aOld[0]));
        $oUnified->setOldLength((int)$aOld[1]);

        $oUnified->setNewStart((int)str_replace('+', '', $aNew[0]));
        $oUnified->setNewLength((int)$aNew[1]);

        return $oUnified;
    }

    /**
     * Return the difference lines.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getLines()
    {
        return $this->aLines;
    }
}
