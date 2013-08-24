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
 * @since      File available since Release 3.0.0
 */

namespace HookTest\Adapter\Git\Parser;

use Hook\Adapter\Git\Parser\Lines;

require_once __DIR__ . '/../../../../Bootstrap.php';

/**
 * Test class for parsing the diff of a property change set.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
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
     * @param string $sFile Transaction number.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function readTestData($sFile)
    {
        $sFile  = __DIR__ . '/_files/Lines/' . $sFile;
        $aLines = file($sFile, FILE_IGNORE_NEW_LINES);

        // Like in Hook\Adapter\Git\Parser::handle function for the last change block.
        // For the last block always put a blank line.
        $aLines[] = '';

        // The first line is removed by class Hook\Adapter\Git\Parser.
        array_shift($aLines);

        return $aLines;
    }

    /**
     * Implement testParse.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testAddedParts()
    {
        // Parse diff lines.
        $this->oLines->parse($this->readTestData('Added.txt'));

        $aLines = $this->oLines->getLines();

        $this->assertCount(1, $aLines, 'Only one section expected.');

        // Check the object.
        $oExpected = null;
        require_once __DIR__ . '/_files/Lines/Expected-Added.php';

        $this->assertEquals($oExpected, $aLines[0], 'Object not as expected.');
    }

    /**
     * Another test Parse
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testDeletedParts()
    {
        // Parse diff lines.
        $this->oLines->parse($this->readTestData('Deleted.txt'));

        $aLines = $this->oLines->getLines();

        $this->assertCount(1, $aLines, 'Only one section expected.');

        // Check the object.
        $oExpected = null;
        require_once __DIR__ . '/_files/Lines/Expected-Deleted.php';

        $this->assertEquals($oExpected, $aLines[0], 'Object not as expected.');
    }

    /**
     * Test the parse when all change lines are the same.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testMixedParts()
    {
        // Parse diff lines.
        $this->oLines->parse($this->readTestData('Mixed.txt'));

        $aLines = $this->oLines->getLines();

        $this->assertCount(3, $aLines, '3 different sections expected.');

        // Check the objects.
        $oExpected1 = null;
        $oExpected2 = null;
        $oExpected3 = null;
        require_once __DIR__ . '/_files/Lines/Expected-Mixed.php';

        $this->assertEquals($oExpected1, $aLines[0], 'Object 0 not as expected.');
        $this->assertEquals($oExpected2, $aLines[1], 'Object 1 not as expected.');
        $this->assertEquals($oExpected3, $aLines[2], 'Object 2 not as expected.');
    }

    /**
     * Test parse a binary diff
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParseBinaryDiff()
    {
        $this->markTestIncomplete('Example missing');

        // Parse diff lines.
        $this->oLines->parse($this->readTestData('1719-1'));

        $this->assertEmpty($this->oLines->getLines());
    }
}