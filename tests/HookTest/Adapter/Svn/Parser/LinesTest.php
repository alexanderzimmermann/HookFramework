<?php
/**
 * Test class for parsing the diff of a property change set.
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

namespace HookTest\Commit\Parser;

use Hook\Adapter\Svn\Parser\Lines;

require_once __DIR__ . '/../../../../Bootstrap.php';

/**
 * Test class for parsing the diff of a property change set.
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
class LinesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Lines Parser.
     * @var Lines
     */
    private $oLines;

    /**
     * Sets up the fixture.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUp()
    {
        $this->oLines = new Lines();
    }

    /**
     * Read test data.
     * @param string $sTxn Transaction number.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function readTestData($sTxn)
    {
        $sFile = HF_TEST_FILES_DIR . '/txn/' . $sTxn . '/diff.txt';

        $sLines = file_get_contents($sFile);
        $aLines = explode("\n", $sLines);

        // Like in Hook\Commit\Parser\Parser::handle function for the last change block.
        // For the last block always put a blank line.
        $aLines[] = '';

        // Those lines were removed by class Hook\Commit\Parser.
        array_shift($aLines);
        array_shift($aLines);

        return $aLines;
    }

    /**
     * Implement testParse.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParseLinesOnlyAddedParts()
    {
        // Parse diff lines.
        $this->oLines->parse($this->readTestData('10-k'));

        $aLines = $this->oLines->getLines();

        $oExpected = null;
        require_once __DIR__ . '/_files/Lines/Expected10k-area-1.php';

        $this->assertEquals($oExpected, $aLines[0], 'area - 1 wrong');

        $oExpected = null;
        require_once __DIR__ . '/_files/Lines/Expected10k-area-2.php';

        $this->assertEquals($oExpected, $aLines[1], 'area - 2 wrong');
    }

    /**
     * Another test Parse
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParseLinesMixed()
    {
        // Parse diff lines.
        $this->oLines->parse($this->readTestData('11-b'));

        $aLines = $this->oLines->getLines();

        $oExpected = null;
        require_once __DIR__ . '/_files/Lines/Expected11b-area-1.php';

        $this->assertEquals($oExpected, $aLines[0], 'area - 1 wrong');

        $oExpected = null;
        require_once __DIR__ . '/_files/Lines/Expected11b-area-2.php';

        $this->assertEquals($oExpected, $aLines[1], 'area - 2 wrong');
    }

    /**
     * Test the parse when all change lines are the same.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParseLinesMixedPartsAtSameRow()
    {
        // Parse diff lines.
        $this->oLines->parse($this->readTestData('16-g'));

        $aLines = $this->oLines->getLines();

        $oExpected = null;
        require_once __DIR__ . '/_files/Lines/Expected16g-area-1.php';

        $this->assertEquals($oExpected, $aLines[0], 'area - 1 wrong');
    }

    /**
     * Test parse a binary diff
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParseBinaryDiff()
    {
        // Parse diff lines.
        $this->oLines->parse($this->readTestData('1719-1'));

        $this->assertEmpty($this->oLines->getLines());
    }
}