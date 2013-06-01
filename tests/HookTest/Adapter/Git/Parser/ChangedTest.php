<?php
/**
 * Test class for parsing the changed items of a commit.
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

namespace Hook\Adapter\Git\Parser;

use Hook\Adapter\Git\Parser\Changed;

require_once __DIR__ . '/../../../../Bootstrap.php';

/**
 * Test class for parsing the changed items of a commit.
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
class ChangedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The changed items parser.
     * @var Changed
     */
    protected $oChanged;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->oChanged = new Changed;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * Test that the git diff raw output is parsed correctly.
     * @covers Hook\Adapter\Git\Parser\Changed::parseFiles
     */
    public function testParseFilesSimple()
    {
        $aObjects = array();
        $aLines   = file(__DIR__ . '/../../../_files/git/Parser/Changed/git-diff-simple.txt');
        $this->oChanged->parseFiles($aLines);
        $oActual = $this->oChanged->getObjects();

        // Includes $aObjects array.
        include __DIR__ . '/_files/expected-simple.php';

        $oExpected = new \ArrayObject();
        foreach ($aObjects as $aObject) {

            unset($aObject['raw-data']);
            $oExpected->append($aObject);
        }

        $this->assertEquals($oExpected, $oActual);
    }

    /**
     * Test that the git diff raw output is parsed correctly.
     * @covers Hook\Adapter\Git\Parser\Changed::parseFiles
     */
    public function testParseFilesExample()
    {
        $aLines = file(__DIR__ . '/../../../_files/git/Parser/Changed/git-diff-example.txt');

        $this->oChanged->parseFiles($aLines);

        // We check object 45
        $aElements = $this->oChanged->getObjects();

        $aExpected = array(
            'isdir'  => false,
            'txn'    => '',
            'rev'    => '',
            'action' => 'A',
            'item'   => 'library/Hook/Commit/Base.php',
            'real'   => 'library/Hook/Commit/Base.php',
            'ext'    => 'PHP',
            'info'   => '',
            'props'  => null,
            'lines'  => array()
        );

        $this->assertSame($aExpected, $aElements[45]);
    }
}
