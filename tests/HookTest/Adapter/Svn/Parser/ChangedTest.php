<?php
/**
 * Test class for parsing the changed items of a commit.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn\Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace HookTest\Adapter\Svn\Parser;

use Hook\Adapter\Svn\Parser\Changed;

require_once __DIR__ . '/../../../../Bootstrap.php';

/**
 * Test class for parsing the changed items of a commit.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn\Parser
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
        $aLines   = file(HF_TEST_FILES_DIR . 'txn/74-1/changed.txt');

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
        $aLines = file(HF_TEST_FILES_DIR . 'txn/110-1/changed.txt');

        $this->oChanged->parseFiles($aLines);

        $aElements = $this->oChanged->getObjects();

        // We check object 11 and 14.
        $aExpected = array(
            'txn'    => '',
            'rev'    => '',
            'action' => 'D',
            'item'   => 'trunk/Core/CommitParser.php',
            'real'   => 'Core/CommitParser.php',
            'ext'    => 'PHP',
            'isdir'  => false,
        );

        $this->assertSame($aExpected, $aElements[10], 'Element 11 failed');

        $aExpected = array(

            'txn'    => '',
            'rev'    => '',
            'action' => 'A',
            'item'   => 'trunk/Core/Listener/',
            'real'   => 'Core/Listener/',
            'ext'    => '',
            'isdir'  => true,
        );

        $this->assertSame($aExpected, $aElements[13], 'Element 14 failed');
    }

    /**
     * Test parse realpath correctly.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParseRealPath()
    {
        $aLines = file(__DIR__ . '/_files/realpath-changed.txt');

        $this->oChanged->parseFiles($aLines);

        $aElements = $this->oChanged->getObjectsArray();

        // We check 3 lines.
        $aExpected = array(
            array(
                'txn'    => '',
                'rev'    => '',
                'action' => 'U',
                'item'   => '/trunk/NewFolder/NewFolder-1/NewFolder-1-1/File-1.php',
                'real'   => 'NewFolder/NewFolder-1/NewFolder-1-1/File-1.php',
                'ext'    => 'PHP',
                'isdir'  => false,
            ),
            array(
                'txn'    => '',
                'rev'    => '',
                'action' => 'U',
                'item'   => '/branches/1.0/NewFolder/NewFolder-1/NewFolder-1-1/File-2.php',
                'real'   => 'NewFolder/NewFolder-1/NewFolder-1-1/File-2.php',
                'ext'    => 'PHP',
                'isdir'  => false,
            ),
            array(
                'txn'    => '',
                'rev'    => '',
                'action' => 'U',
                'item'   => '/tags/1.1.1/NewFolder/NewFolder-1/NewFolder-1-1/File-3.php',
                'real'   => 'NewFolder/NewFolder-1/NewFolder-1-1/File-3.php',
                'ext'    => 'PHP',
                'isdir'  => false,
            ),
            array(
                'txn'    => '',
                'rev'    => '',
                'action' => 'U',
                'item'   => 'trunk/NewFolder/NewFolder-1/NewFolder-1-1/File-1.php',
                'real'   => 'NewFolder/NewFolder-1/NewFolder-1-1/File-1.php',
                'ext'    => 'PHP',
                'isdir'  => false,
            ),
            array(
                'txn'    => '',
                'rev'    => '',
                'action' => 'U',
                'item'   => 'branches/1.0/NewFolder/NewFolder-1/NewFolder-1-1/File-2.php',
                'real'   => 'NewFolder/NewFolder-1/NewFolder-1-1/File-2.php',
                'ext'    => 'PHP',
                'isdir'  => false,
            ),
            array(
                'txn'    => '',
                'rev'    => '',
                'action' => 'U',
                'item'   => 'tags/1.1.1/NewFolder/NewFolder-1/NewFolder-1-1/File-3.php',
                'real'   => 'NewFolder/NewFolder-1/NewFolder-1-1/File-3.php',
                'ext'    => 'PHP',
                'isdir'  => false,
            )
        );
        $this->assertSame($aExpected, $aElements);
    }
}
