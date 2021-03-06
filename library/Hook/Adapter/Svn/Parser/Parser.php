<?php
/**
 * Parser for the differences of the transaction / revision.
 * @category   Adapter
 * @package    Svn
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter\Svn\Parser;

use Hook\Adapter\Svn\Parser\Lines;
use Hook\Adapter\Svn\Parser\Properties;

/**
 * Parser for the differences of the whole commit.
 * Divides the lines into its parts of each file in the commit.
 * @category   Adapter
 * @package    Svn
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Parser
{
    /**
     * The line parser object.
     * @var Lines
     */
    private $oLines;

    /**
     * The property parser object.
     * @var Properties
     */
    private $oProperties;

    /**
     * Head for line change.
     * @var string
     */
    private $sLinesHead;

    /**
     * Head for property changes.
     * @var string
     */
    private $sPropertyHead;

    /**
     * Changed objects.
     * @var array
     */
    private $aObjects;

    /**
     * Difference lines of commit.
     * @var array
     */
    private $aLines = array();

    /**
     * Multi dimension Array with the diff line objects of each file.
     * @var array
     */
    private $aDiffLines = array();

    /**
     * Constructor.
     * @param array $aObjects Directories and Files in the commit / transaction.
     * @param array $aLines   Difference lines of the commit / transaction.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct(array $aObjects, array $aLines)
    {
        $this->oLines      = new Lines();
        $this->oProperties = new Properties();

        $this->sLinesHead    = str_repeat('=', 67);
        $this->sPropertyHead = str_repeat('_', 67);

        $this->aObjects = $aObjects;
        $this->aLines   = $aLines;
    }

    /**
     * Parse the diff lines of that commit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parse()
    {
        $iOffset     = false;
        $aLines      = array();
        $bLines      = false;
        $bProperties = false;

        foreach ($this->aLines as $iLine => $sLine) {

            // Lines head is 67 signs of = line.
            if ($sLine === $this->sLinesHead) {

                $this->handle($iOffset, $aLines, array($bLines, $bProperties));

                $bLines      = true;
                $bProperties = false;
                $iOffset     = $this->getFile($this->aLines[($iLine - 1)]);
                $aLines      = array();
            }

            // Property head is 67 signs of _ line.
            if ($sLine === $this->sPropertyHead) {

                $this->handle($iOffset, $aLines, array($bLines, $bProperties));

                $bLines      = false;
                $bProperties = true;
                $iOffset     = $this->getFile($this->aLines[($iLine - 1)]);
                $aLines      = array();
            }

            $aLines[] = $sLine;
        }

        // For the last block always put a blank line.
        $aLines[] = '';

        $this->handle($iOffset, $aLines, array($bLines, $bProperties));
    }

    /**
     * Handle the changes between lines and or properties blocks.
     * @param integer $iOffset Offset of the file.
     * @param array   $aLines  The collected lines of the block.
     * @param array   $aBlock  Which block was active.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function handle($iOffset, array $aLines, array $aBlock)
    {
        if ((false !== $iOffset) && (false === empty($aLines))) {

            // First Line is always the -- or == line.
            array_shift($aLines);

            // Last line is the info of the next file.
            if ('' !== $aLines[(count($aLines) - 1)]) {
                array_pop($aLines);
            }

            // Parse the changed lines block.
            if (true === $aBlock[0]) {
                $this->oLines->parse($aLines);
                $this->aDiffLines[$iOffset] = $this->oLines->getLines();
            }

            // Parse the properties lines block.
            if (true === $aBlock[1]) {
                $this->oProperties->parse($iOffset, $aLines);
            }
        }
    }

    /**
     * Determine the file of the changed lines / properties.
     * Example line: Added: trunk/Core/Commit/CommitBase.php
     * @param string $sLine The line of the changed lines / properties.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getFile($sLine)
    {
        $aParts  = explode(':', $sLine);
        $sFile   = trim($aParts[1]);
        $iOffset = array_search($sFile, $this->aObjects);

        // Another check cause with properties the ending / is missing in the $sLine.
        if ((false === $iOffset) &&
            (false !== strpos($sLine, 'Property')) &&
            (false === strpos($sLine, '.'))) {

            $sFile  .= '/';
            $iOffset = array_search($sFile, $this->aObjects);
        }

        return $iOffset;
    }

    /**
     * Returns the lines objects.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getLines()
    {
        return $this->aDiffLines;
    }

    /**
     * Returns the property objects.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getProperties()
    {
        return $this->oProperties->getProperties();
    }
}
