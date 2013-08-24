<?php
/**
 * Parser for the differences of the transaction.
 * @category   Adapter
 * @package    Git
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter\Git\Parser;

use Hook\Adapter\Git\Parser\Lines;

/**
 * Parser for the differences of the whole commit.
 * Divides the lines into its parts of each file in the commit.
 * @category   Adapter
 * @package    Git
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
     * @param array $aObjects Directories and Files in the commit .
     * @param array $aLines   Difference lines of the commit.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct(array $aObjects, array $aLines)
    {
        $this->oLines   = new Lines();
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

        foreach ($this->aLines as $sLine) {

            if ('diff --git' === substr($sLine, 0, 10)) {

                $this->handle($iOffset, $aLines);

                $iOffset = $this->getFile($sLine);
                $aLines  = array();
            }

            $aLines[] = $sLine;
        }

        // For the last block always put a blank line.
        $aLines[] = '';

        $this->handle($iOffset, $aLines);
    }

    /**
     * Handle the changes between lines and or properties blocks.
     * @param integer $iOffset Offset of the file.
     * @param array   $aLines  The collected lines of the block.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function handle($iOffset, array $aLines)
    {
        if ((false !== $iOffset) && (false === empty($aLines))) {

            // First Line ist the "git --diff...".
            array_shift($aLines);

            // Last line is the info of the next file.
            if ('' !== $aLines[(count($aLines) - 1)]) {
                array_pop($aLines);
            }

            // Parse the changed lines block.
            $this->oLines->parse($aLines);
            $this->aDiffLines[$iOffset] = $this->oLines->getLines();
        }
    }

    /**
     * Determine the file of the changed lines.
     * Example line: :100755 000000 f6cc9f7... 0000000... D	tests/HookTest/_files/bin/git
     * We take the file from the diff lines and search it in the changed lines to be sure all is ok.
     * From: diff --git a/tests/HookTest/_files/bin/git b/tests/HookTest/_files/bin/git
     * To:   tests/HookTest/_files/bin/git
     * We take the a part.
     * @param string $sLine The line of the changed lines / properties.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getFile($sLine)
    {
        $aParts  = explode(' ', $sLine);
        $sFile   = trim(str_replace('a/', '', $aParts[2]));
        $iOffset = array_search($sFile, $this->aObjects);

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
}
