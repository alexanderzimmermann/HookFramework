<?php
/**
 * Parser Tests.
 *
 * This class is more like an integration test for lines and changed parser.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn\Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Adapter\Git\Parser;

use Hook\Adapter\Git\Parser\Changed;
use Hook\Adapter\Git\Parser\Parser;

require_once __DIR__ . '/../../../../Bootstrap.php';

/**
 * Parser Tests.
 *
 * This class is more like an integration test for lines and changed parser.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn\Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepare commit.
     * @param string $sSha1 Transaction identifier.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function prepareCommit($sSha1)
    {
        // Get the changed files/directories entries.
        $sDir     = HF_TEST_FILES_DIR . '/git/' . $sSha1 . '/';
        $aChanged = file($sDir . 'changed.txt', FILE_IGNORE_NEW_LINES);
        $aDiff    = file($sDir . '/diff.txt', FILE_IGNORE_NEW_LINES);

        return array(
                'changed' => $aChanged,
                'diff'    => $aDiff
               );
    }

    /**
     * Test a commit with simple data.
     *
     * We do not check the sub-objects like Diff or Lines.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testCommit1()
    {
        $aData = $this->prepareCommit('123456');

        // Parse array with the changed items.
        $oChanged = new Changed();
        $oChanged->parseFiles($aData['changed']);

        $oParser = new Parser($oChanged->getFiles(), $aData['diff']);
        $oParser->parse();

        // The first array is the index for each file in the commit.
        // For each diff part in a file (marked with @@) is also an index available.
        $aLines = $oParser->getLines();

        // So we check, 0 = first file,
        $this->assertCount(2, $aLines, 'Files count');
        $this->assertCount(1, $aLines[0], 'Diff parts wrong');
    }

    /**
     * Test a commit
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testCommit2()
    {
        $aData = $this->prepareCommit('d3c57c9bce575082af8b7a0bb6d2f836a46cb4a5');

        // Parse array with the changed items.
        $oChanged = new Changed();
        $oChanged->parseFiles($aData['changed']);

        $oParser = new Parser($oChanged->getFiles(), $aData['diff']);
        $oParser->parse();

        // The first array is the index for each file in the commit.
        // For each diff part in a file (marked with @@) is also an index available.
        $aLines = $oParser->getLines();

        // So we check, 0 = first file,
        $this->assertCount(7, $aLines, 'Files count');
        $this->assertCount(3, $aLines[0], 'Diff parts wrong');
    }
}
