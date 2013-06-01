<?php
/**
 * Test class for parsing the info of a commit..
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

use Hook\Adapter\Svn\Parser\Info;
use Hook\Commit\Info as InfoObject;

require_once __DIR__ . '/../../../../Bootstrap.php';

/**
 * Test class for parsing the info of a commit.
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
class InfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The changed items parser.
     * @var Info
     */
    protected $oInfo;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * @return void
     */
    protected function setUp()
    {
        $this->oInfo = new Info;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * @return void
     */
    protected function tearDown()
    {
    }

    /**
     * Test when the info is empty.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testEmpty()
    {
        $oExpected = new InfoObject('', '', '', '', '');
        $oInfo     = $this->oInfo->parse(array(), '', '');

        $this->assertEquals($oInfo, $oExpected);
    }

    /**
     * Test standard info, user, message.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testStandard()
    {
        $aData     = file(HF_TEST_FILES_DIR . 'txn/10-k/info.txt');
        $oExpected = new InfoObject(
            '10-k',
            '10',
            'duchess',
            '2012-11-20 21:23:01 +0100 (Tue, 20 Nov 2012)',
            '+ Added some code.'
        );

        $oInfo = $this->oInfo->parse($aData, '10-k', '10');

        $this->assertEquals($oInfo, $oExpected);
    }

    /**
     * Test special chars
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSpecialChars()
    {
        $aData     = file(__DIR__ . '/../../../_files/txn/74-1/info.txt');
        $oExpected = new InfoObject(
            '74-1',
            '74',
            'alexander',
            '2008-12-20 20:20:58 +0100 (Sat, 20 Dec 2008)',
            '* Testcommit mit 3 Dateien und zwei geänderten Properties.'
        );

        $oInfo = $this->oInfo->parse($aData, '74-1', '74');

        $this->assertEquals($oInfo, $oExpected);
    }

    /**
     * Test missing commit message
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testMissingCommitMessage()
    {
        $aData     = array('alexander', '2009-01-03 13:11:06 +0100 (Sat, 03 Jan 2009)');
        $oExpected = new InfoObject(
            '74-1',
            '74',
            'alexander',
            '2009-01-03 13:11:06 +0100 (Sat, 03 Jan 2009)',
            ''
        );

        $oInfo = $this->oInfo->parse($aData, '74-1', '74');

        $this->assertEquals($oInfo, $oExpected);
    }
}
