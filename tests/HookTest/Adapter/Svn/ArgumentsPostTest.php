<?php
/**
 * Argument Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Adapter\Svn;

use Hook\Adapter\Svn\Arguments;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Argument Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
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
     * Test if values are returned correctly..
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetValuesPostCommit()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => TEST_SVN_EXAMPLE,
            2 => 666,
            3 => 'post-commit'
        );

        $oArguments = new Arguments($aData);

        $this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
        $this->assertEquals(TEST_SVN_EXAMPLE, $oArguments->getRepository(), 'Repository false');
        $this->assertEquals('Example', $oArguments->getRepositoryName(), 'ReposName false');
        $this->assertEquals(666, $oArguments->getRevision(), 'Revision false');
        $this->assertEquals('post-commit', $oArguments->getMainHook(), 'MainHook false');
        $this->assertEquals('post', $oArguments->getMainType(), 'MainType false');
        $this->assertEquals('commit', $oArguments->getSubType(), 'SubType false');
    } // function

    /**
     * Dataprovider.
     * @return array
     * @author Alexander Zimmermann <alex@zimmemann.com>
     */
    public static function getPostCommitArguments()
    {
        return array(
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => TEST_SVN_EXAMPLE,
                    'rev'   => '666',
                    'hook'  => 'post-commit',
                ), true
            ),
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => TEST_SVN_EXAMPLE,
                    'rev'   => '',
                    'hook'  => 'post-commit',
                ), false
            ),
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => TEST_SVN_EXAMPLE,
                    'rev'   => 'testuser',
                    'hook'  => 'post-commit',
                ), false
            ),
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => '/x/x/svn',
                    'rev'   => '666-1',
                    'hook'  => 'post-commit',
                ), false
            )
        );
    } // function

    /**
     * Test arguments in pre commit.
     * @param array   $aData     Test data.
     * @param boolean $bExpected Expected assert.
     * @return void
     * @author       Alexander Zimmermann <alex@azimmermann.com>
     * @dataProvider getPostCommitArguments
     */
    public function testPostCommitArguments(array $aData, $bExpected)
    {
        $aData = array(
            $aData['path'],
            $aData['repos'],
            $aData['rev'],
            $aData['hook']
        );

        $oArguments = new Arguments($aData);
        $this->assertEquals($bExpected, $oArguments->argumentsOk(), $oArguments->getError());
    } // function
} // class
