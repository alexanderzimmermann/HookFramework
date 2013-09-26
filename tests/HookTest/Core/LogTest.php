<?php
/**
 * Tests for log object.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace HookTest\Core;

use Hook\Core\Log;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Tests for log object.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class LogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Log instance..
     * @var Log
     */
    private $oFixture;

    /**
     * Stream resource.
     * @var resource
     */
    private $rFile;

    /**
     * Setup
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUp()
    {
        $sFile          = 'php://memory';
        $this->oFixture = Log::getInstance();
        $this->rFile    = $this->oFixture->setLogFile($sFile);
    }

    /**
     * Test that the log instance has a valid log file.
     */
    public function testLogfileAvailable()
    {
        $this->assertTrue($this->oFixture->hasLogFile());
    }

    /**
     * Test that an exception is thrown.
     */
    public function testWriteErrorFile()
    {
        $sMessage = 'fopen(/no-file.txt): failed to open stream: Permission denied';
        $this->setExpectedException('Exception', $sMessage);

        $this->oFixture->setLogFile('/no-file.txt');
    }

    /**
     * Test check values on create
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetInstance()
    {
        $oLog = Log::getInstance();

        $this->assertSame('Hook\Core\Log', get_class($oLog));
    }

    /**
     * Test that clone throws exception
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testThrowsException()
    {
        $this->setExpectedException('\Exception', 'Not allowed');

        $oLog = Log::getInstance();
        $oTmp = clone $oLog;
        unset($oTmp);
    }

    /**
     * Test that the same object is returned.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetSameObject()
    {
        $this->assertSame(Log::getInstance('test'), Log::getInstance('test'));
    }

    /**
     * Test that a different object is returned.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetDifferentObject()
    {
        $this->assertNotSame(Log::getInstance('test-a'), Log::getInstance('test-b'));
    }

    /**
     * Data provider for log mode settings
     */
    public function providerLogModeSettings()
    {
        return array(
                array(Log::HF_INFO, Log::HF_INFO, 'A info message', null),
                array(Log::HF_DEBUG, Log::HF_DEBUG, 'A debug message', null),
                array(Log::HF_VARDUMP, Log::HF_VARDUMP, 'A var dump message', null),
                array(Log::HF_INFO, Log::HF_DEBUG, 'A debug message', ''),
                array(Log::HF_DEBUG, Log::HF_VARDUMP, 'A var dump message', '')
               );
    }

    /**
     * Test log mode settings.
     * @param integer $iMode     Log mode
     * @param integer $iLevel    Log level for that message.
     * @param string  $sMessage  Message to log.
     * @param string  $sExpected Expected log message.
     * @dataProvider providerLogModeSettings
     */
    public function testLogModeSettings($iMode, $iLevel, $sMessage, $sExpected)
    {
        $this->oFixture->setLogMode($iMode);
        $this->oFixture->writeLog($iLevel, $sMessage);

        // Now check, Trim the first 3 lines.
        rewind($this->rFile);
        $aLines = explode(PHP_EOL, stream_get_contents($this->rFile));

        // I hate it, but it makes it easier.
        if (null === $sExpected) {
            $sExpected = $sMessage;
        }

        $this->assertSame($sExpected, $aLines[3]);
    }

    /**
     * Test a var_dump log.
     */
    public function testVarDumpLog()
    {
        $aDump = array(
                  0       => 'Some data',
                  'index' => 'with some data.'
                 );

        $this->oFixture->setLogMode(Log::HF_VARDUMP);
        $this->oFixture->writeLog(Log::HF_VARDUMP, 'var-dump', $aDump);

        // Now check, Trim the first 3 lines.
        rewind($this->rFile);
        $aLines = explode(PHP_EOL, stream_get_contents($this->rFile));
        $aLines = array_slice($aLines, 3);

        $aExpected = array(
                      'var-dump',
                      'array (',
                      '  0 => \'Some data\',',
                      '  \'index\' => \'with some data.\',',
                      ')',
                      ''
                     );

        rewind($this->rFile);
        $this->assertSame($aExpected, $aLines);
    }
}
