<?php
/**
 * Style Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Listener\Pre;

use Hook\Commit\Info;
use Hook\Commit\Object;
use Hook\Adapter\Svn\Parser\Lines;

use ExampleSvn\Pre\StyleIncrement;

require_once __DIR__ . '/../../../Bootstrap.php';

require_once __DIR__ . '/../../../../Repositories/ExampleSvn/Pre/StyleIncrement.php';

/**
 * Style Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class StyleIncrementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test object Style Listener.
     * @var StyleIncrement
     */
    private $oStyleListener;

    /**
     * Configuration array.
     * @var array
     */
    private $aCfg;

    /**
     * SetUp operations.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUp()
    {
        $aCfg = array(
            'Standard' => 'PSR2',
        );

        $this->aCfg = $aCfg;

        $this->oStyleListener = new StyleIncrement();
        $this->oStyleListener->setConfiguration($aCfg);
    }

    /**
     * Check that the pear package PHP_CodeSniffer is available and the PSR2 is installed.
     * If not mark the test skipped.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function checkCodeSniffer()
    {
        $aOutput = array();
        exec('phpcs --standard=PSR2 ' . __FILE__, $aOutput);

        if (true === empty($aOutput)) {
            $this->markTestSkipped('phpcs or PSR2 standard not installed!');
        }

        if (count($aOutput) === 1) {
            $sMsg = 'ERROR: the "PSR2" coding standard is not installed.';
            if (substr($aOutput[0], 0, 50) === $sMsg) {
                $this->markTestSkipped('PEAR Standard not installed!');
            }
        }

        return $aOutput;
    }

    /**
     * Test the style listener with a "wrong" file.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testListenerStyleWithOkFile()
    {
        $sFile = __DIR__ . '/../../_files/txn/11-b/WhiteFile.php.txt';

        // Is PEAR Package phpcs and the PEAR Standard for test installed.
        $this->checkCodeSniffer();

        // Create info object
        $sMsg  = '* Comment to this commit';
        $oInfo = new Info('11-b', 666, 'Alice', '2012-11-28 12:22:23', $sMsg);

        // Create Diff Lines.
        $oLines = new Lines();
        $aLines = file(__DIR__ . '/../../_files/txn/11-b/diff.txt');

        array_shift($aLines);
        array_shift($aLines);

        // Parse diff lines for file 1.
        $oLines->parse($aLines);
        $aLines = $oLines->getLines();

        $aParams = array(
            'txn'    => '11-b',
            'rev'    => 12,
            'action' => 'U',
            'item'   => $sFile,
            'real'   => $sFile,
            'ext'    => 'txt',
            'isdir'  => false,
            'props'  => array(),
            'lines'  => $aLines,
            'info'   => $oInfo
        );

        $oObject  = new Object($aParams);
        $sTmpPath = $oObject->getTmpObjectPath();

        copy($sFile, $sTmpPath);

        $this->oStyleListener->processAction($oObject);

        // Clean up.
        unlink($sTmpPath);

        $aData = $oObject->getErrorLines();

        $aExp = array(
            ' 38 | ERROR | Line indented incorrectly; expected at least 4 spaces, found 2',
            ' 39 | ERROR | Line indented incorrectly; expected at least 4 spaces, found 2',
            ' 41 | ERROR | Spaces must be used to indent lines; tabs are not allowed' . "\n" .
            ' 41 | ERROR | Line indented incorrectly; expected at least 4 spaces, found 1',
            ' 53 | ERROR | Line indented incorrectly; expected at least 4 spaces, found 2',
            ' 54 | ERROR | Line indented incorrectly; expected at least 4 spaces, found 2',
            ' 55 | ERROR | Line indented incorrectly; expected at least 4 spaces, found 2',
            ' 56 | ERROR | Spaces must be used to indent lines; tabs are not allowed' . "\n" .
            ' 56 | ERROR | Line indented incorrectly; expected at least 4 spaces, found 1' . "\n" .
            ' 56 | ERROR | Line indented incorrectly; expected 4 spaces, found 1',
            ' 57 | ERROR | Spaces must be used to indent lines; tabs are not allowed',
            ' 58 | ERROR | Spaces must be used to indent lines; tabs are not allowed' . "\n" .
            ' 58 | ERROR | Line indented incorrectly; expected at least 8 spaces, found 2',
            ' 59 | ERROR | Spaces must be used to indent lines; tabs are not allowed',
            ' 61 | ERROR | Spaces must be used to indent lines; tabs are not allowed' . "\n" .
            ' 61 | ERROR | Line indented incorrectly; expected at least 4 spaces, found 1',
            ' 67 | ERROR | Spaces must be used to indent lines; tabs are not allowed' . "\n" .
            ' 67 | ERROR | Line indented incorrectly; expected at least 8 spaces, found 2',
            ' 68 | ERROR | Spaces must be used to indent lines; tabs are not allowed' . "\n" .
            ' 68 | ERROR | Line indented incorrectly; expected at least 8 spaces, found 2',
            ' 72 | ERROR | Spaces must be used to indent lines; tabs are not allowed' . "\n" .
            ' 72 | ERROR | Line indented incorrectly; expected at least 12 spaces, found 3',
            "\n"
        );

        $this->assertSame($aExp, $aData);
    }

    /**
     * Test the incremental style listener with a non PSR2 standard file.
     * But some lines do now fit the style guide PSR2, but only the changed lines.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testListenerStyleWithErrorFile()
    {
        $sFile = __DIR__ . '/../../_files/txn/16-g/NonPsr2File.php.txt';

        // Is PEAR Package phpcs and the PEAR Standard for test installed.
        $this->checkCodeSniffer();

        // Create info object
        $sMsg  = '* Comment to this commit';
        $oInfo = new Info('16-g', 666, 'Enya', '2012-11-28 12:22:23', $sMsg);

        // Create Diff Lines.
        $oLines = new Lines();
        $aLines = file(__DIR__ . '/../../_files/txn/16-g/diff.txt');

        array_shift($aLines);
        array_shift($aLines);

        // Parse diff lines for file 1.
        $oLines->parse($aLines);
        $aLines = $oLines->getLines();

        $aParams = array(
            'txn'    => '16-g',
            'rev'    => null,
            'action' => 'U',
            'item'   => $sFile,
            'real'   => $sFile,
            'ext'    => 'txt',
            'isdir'  => false,
            'props'  => array(),
            'lines'  => $aLines,
            'info'   => $oInfo
        );

        $oObject  = new Object($aParams);
        $sTmpPath = $oObject->getTmpObjectPath();

        copy($sFile, $sTmpPath);

        $this->oStyleListener->processAction($oObject);

        // Clean up.
        unlink($sTmpPath);

        $aData = $oObject->getErrorLines();

        $aExp = array(
            "  2 | ERROR | Each class must be in a namespace of at least one level (a\n" .
            '    |       | top-level vendor name)',
            '  4 | ERROR | Line indented incorrectly; expected at least 4 spaces, found 1',
            '  8 | ERROR | Line indented incorrectly; expected at least 8 spaces, found 3',
            ' 11 | ERROR | Line indented incorrectly; expected at least 4 spaces, found 1' . "\n" .
            ' 11 | ERROR | Line indented incorrectly; expected 4 spaces, found 1',
            "\n"
        );

        $this->assertSame($aExp, $aData);
    }
}
