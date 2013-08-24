<?php
/**
 * Parser Tests.
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

namespace HookTest\Adapter\Svn\Parser;

use Hook\Adapter\Svn\Arguments;
use Hook\Adapter\Svn\Command;
use Hook\Adapter\Svn\Parser\Changed;
use Hook\Adapter\Svn\Parser\Parser;
use Hook\Commit\Data;

require_once __DIR__ . '/../../../../Bootstrap.php';

/**
 * Parser Tests.
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
     * @param string $sTxn Transaction identifier.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function prepareCommit($sTxn)
    {
        // Get the changed files/directories entries.
        $sDir     = HF_TEST_FILES_DIR . '/txn/' . $sTxn . '/';
        $aChanged = file($sDir . 'changed.txt', FILE_IGNORE_NEW_LINES);
        $aDiff    = file($sDir . '/diff.txt', FILE_IGNORE_NEW_LINES);

        return array(
            'changed' => $aChanged,
            'diff'    => $aDiff
        );
    }

    /**
     * Test one file changed and one changed area.
     *
     * We do not check the sub-objects like Diff or Lines.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testOneChangedFile()
    {
        $aData = $this->prepareCommit('10-k');

        // Parse array with the changed items.
        $oChanged = new Changed();
        $oChanged->parseFiles($aData['changed']);

        $oParser = new Parser($oChanged->getFiles(), $aData['diff']);
        $oParser->parse();

        // The first array is the index for each file in the commit.
        // For each diff part in a file (marked with @@) is also an index available.
        $aLines = $oParser->getLines();

        // So we check, 0 = first file,
        $this->assertCount(1, $aLines, 'Files count');
        $this->assertCount(2, $aLines[0], 'Diff parts wrong');
    }

    /**
     * Pre Commit Test.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testPreCommit74Ok()
    {
        $aData = $this->prepareCommit('74-1');

        // Parse array with the changed items.
        $oChanged = new Changed();
        $oChanged->parseFiles($aData['changed']);

        $oParser = new Parser($oChanged->getFiles(), $aData['diff']);
        $oParser->parse();

        // The first array is the index for each file in the commit.
        // For each diff part in a file (marked with @@) is also an index available.
        $aLines = $oParser->getLines();

        // So we check, 0 = first file,
        $this->assertCount(3, $aLines, 'Files count');
        $this->assertCount(1, $aLines[0], 'Diff parts wrong File 1');
        $this->assertCount(2, $aLines[1], 'Diff parts wrong File 2');
        $this->assertCount(2, $aLines[2], 'Diff parts wrong File 3');
    }

    /**
     * Pre- Commit Test with multiple files and directories.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testPreCommit110MultipleData()
    {
        $aData = $this->prepareCommit('110-1');

        // Parse array with the changed items.
        $oChanged = new Changed();
        $oChanged->parseFiles($aData['changed']);

        $oParser = new Parser($oChanged->getFiles(), $aData['diff']);
        $oParser->parse();

        // The first array is the index for each file in the commit.
        // For each diff part in a file (marked with @@) is also an index available.
        $aLines      = $oParser->getLines();
        $aProperties = $oParser->getProperties();

        // Check.
        // TODO: The directories are missing, should be 25.
        $this->assertCount(23, $aLines, 'Files count');

        $this->assertCount(1, $aLines[1], 'Diff parts wrong File 1');
        $this->assertCount(17, $aLines[12], 'Diff parts wrong File 17');
        $this->assertCount(2, $aLines[24], 'Diff parts wrong File 24');

        // Check properties.
        $this->assertCount(10, $aProperties, 'Properties are wrong');
        $this->assertcount(1, $aProperties[18], 'Properties wrong File 18');
    }

    /**
     * Pre Commit Test Binary.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testPreCommitTxn1719Binary()
    {
        $aData = $this->prepareCommit('1719-1');

        // Parse array with the changed items.
        $oChanged = new Changed();
        $oChanged->parseFiles($aData['changed']);

        $oParser = new Parser($oChanged->getFiles(), $aData['diff']);
        $oParser->parse();

        // The first array is the index for each file in the commit.
        // For each diff part in a file (marked with @@) is also an index available.
        $aLines = $oParser->getLines();

        // Check.
        $this->assertCount(1, $aLines, 'Files count');
        $this->assertCount(0, $aLines[0], 'Diff parts wrong');
    }

    /**
     * Pre Commit Test Delete of a file.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testPreCommitTxn1748DeleteFile()
    {
        $aData = $this->prepareCommit('1748-1');

        // Parse array with the changed items.
        $oChanged = new Changed();
        $oChanged->parseFiles($aData['changed']);

        $oParser = new Parser($oChanged->getFiles(), $aData['diff']);
        $oParser->parse();

        // The first array is the index for each file in the commit.
        // For each diff part in a file (marked with @@) is also an index available.
        $aLines = $oParser->getLines();

        // Check.
        $this->assertCount(1, $aLines, 'Files count');
        $this->assertCount(1, $aLines[0], 'Diff parts wrong');
    }

    /**
     * Pre Commit Test Property Set.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testPreCommitTxn1749PropertyIgnore()
    {
        $aData = $this->prepareCommit('1749-1');

        // Parse array with the changed items.
        $oChanged = new Changed();
        $oChanged->parseFiles($aData['changed']);

        $oParser = new Parser($oChanged->getFiles(), $aData['diff']);
        $oParser->parse();

        // The first array is the index for each file in the commit.
        // For each diff part in a file (marked with @@) is also an index available.
        $aLines      = $oParser->getLines();
        $aProperties = $oParser->getProperties();

        // Check.
        $this->assertCount(0, $aLines, 'Files count');
        $this->assertCount(1, $aProperties[0], 'Diff parts wrong');

        $this->assertCount(1, $aProperties, 'Properties count');
        $this->assertCount(1, $aProperties[0], 'Properties wrong File 18');
    }
}