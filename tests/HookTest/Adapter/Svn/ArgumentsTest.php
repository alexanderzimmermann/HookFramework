<?php
/**
 * Argument Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Adapter\Svn;

use Hook\Adapter\Svn\Arguments;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Argument Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class ArgumentsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test too few arguments.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testToFewArguments()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => '666-1',
            2 => 'pre-commit',
        );

        $oArguments = new Arguments($aData);

        $this->assertFalse($oArguments->argumentsOk());
    }

    /**
     * Test totally wrong arguments.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testWrongArguments()
    {
        $aData = array(
                  '/path/to/Hook', 'io', 'zilli-caramello'
        );

        $oArguments = new Arguments($aData);

        $this->assertFalse($oArguments->argumentsOk());
    }

    /**
     * Test with no arguments provided.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testNoArguments()
    {
        $oArguments = new Arguments(array());

        $this->assertFalse($oArguments->argumentsOk());
    }

    /**
     * Test if values are returned correctly.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testCorrectParameter()
    {
        $sTxn  = '666-1';
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => HF_TEST_SVN_EXAMPLE,
            2 => $sTxn,
            3 => 'Caramello',
            4 => 'Id',
            5 => 'A',
            6 => 'pre-revprop-change',
        );

        $oArguments = new Arguments($aData);

        // Now check all data.
        $this->assertTrue($oArguments->argumentsOk(), $oArguments->getError());
        $this->assertSame('pre-revprop-change', $oArguments->getMainHook(), 'Main hook false.');
        $this->assertSame('revprop-change', $oArguments->getSubType(), 'Sub type false.');
        $this->assertSame('pre', $oArguments->getMainType(), 'Main type false.');
        $this->assertSame(HF_TEST_SVN_EXAMPLE, $oArguments->getRepository(), 'Repository false.');
        $this->assertSame('ExampleSvn', $oArguments->getRepositoryName(), 'Repository Name false.');
        $this->assertSame($sTxn, $oArguments->getTransaction(), 'Txn false.');
    }

    /**
     * Test if values are returned correctly.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSubActions1()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => HF_TEST_SVN_EXAMPLE,
            2 => '666-1',
            3 => 'pre-commit',
        );

        $aExpect = array(
            'commit', 'lock', 'revprop-change', 'unlock'
        );

        $oArguments = new Arguments($aData);

        $this->assertTrue($oArguments->argumentsOk(), 'Arguments false');
        $this->assertEquals($aExpect, $oArguments->getSubActions(), 'Subaction false');
    }

    /**
     * Test if values are returned correctly.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSubActions2()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => HF_TEST_SVN_EXAMPLE,
            2 => 666,
            3 => 'post-commit'
        );

        $aExpect = array(
            'commit', 'lock', 'revprop-change', 'unlock'
        );

        $oArguments = new Arguments($aData);

        $this->assertTrue($oArguments->argumentsOk(), 'Arguments false');
        $this->assertEquals($aExpect, $oArguments->getSubActions(), 'Subaction false');
    }

    /**
     * Test if values are returned correctly.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSubActions3()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => HF_TEST_SVN_EXAMPLE,
            2 => 'Zilli',
            3 => 'start-commit'
        );

        $aExpect = array('commit');

        $oArguments = new Arguments($aData);

        $this->assertTrue($oArguments->argumentsOk(), 'Arguments false');
        $this->assertEquals($aExpect, $oArguments->getSubActions(), 'Subaction false');
    }
}
