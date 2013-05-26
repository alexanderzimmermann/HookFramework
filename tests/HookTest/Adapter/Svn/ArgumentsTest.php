<?php
/**
 * Argument Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
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
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
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
    } // function

    /**
     * Test with no arguments provided.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testNoArguments()
    {
        $oArguments = new Arguments(array());

        $this->assertFalse($oArguments->argumentsOk());
    } // function

    /**
     * Test if values are returned correctly.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSubActions1()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => TEST_SVN_EXAMPLE,
            2 => '666-1',
            3 => 'pre-commit',
        );

        $aExpect = array(
            'commit', 'lock', 'revprop-change', 'unlock'
        );

        $oArguments = new Arguments($aData);

        $this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
        $this->assertEquals($aExpect, $oArguments->getSubActions(), 'Subaction false');
    } // function

    /**
     * Test if values are returned correctly.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSubActions2()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => TEST_SVN_EXAMPLE,
            2 => 666,
            3 => 'post-commit'
        );

        $aExpect = array(
            'commit', 'lock', 'revprop-change', 'unlock'
        );

        $oArguments = new Arguments($aData);

        $this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
        $this->assertEquals($aExpect, $oArguments->getSubActions(), 'Subaction false');
    } // function

    /**
     * Test if values are returned correctly.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSubActions3()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => TEST_SVN_EXAMPLE,
            2 => 'testuser12',
            3 => 'start-commit'
        );

        $aExpect = array('commit');

        $oArguments = new Arguments($aData);

        $this->assertTrue($oArguments->argumentsOk(), 'Arguments false');
        $this->assertEquals($aExpect, $oArguments->getSubActions(), 'Subaction false');
    } // function
} // class
