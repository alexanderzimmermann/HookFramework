<?php
/**
 * Argument tests for start commit.
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
 * Argument tests for start commit.
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
class ArgumentsStartTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test if values are returned correctly.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetValuesStartCommit()
    {
        $aData = array(
            0 => '/var/local/svn/hooks/Hook',
            1 => HF_TEST_SVN_EXAMPLE,
            2 => 'testuser12',
            3 => 'start-commit'
        );

        $oArguments = new Arguments($aData);

        $this->assertTrue($oArguments->argumentsOk(), 'Arguemtns false');
        $this->assertEquals(HF_TEST_SVN_EXAMPLE, $oArguments->getRepository(), 'Repository false');
        $this->assertEquals('ExampleSvn', $oArguments->getRepositoryName(), 'ReposName false');
        $this->assertEquals('testuser12', $oArguments->getUser(), 'User false');
        $this->assertEquals('start-commit', $oArguments->getMainHook(), 'MainHook false');
        $this->assertEquals('start', $oArguments->getMainType(), 'MainType false');
        $this->assertEquals('commit', $oArguments->getSubType(), 'SubType false');
    }

    /**
     * Data provider.
     * @return array
     * @author Alexander Zimmermann <alex@zimmemann.com>
     */
    public static function getStartArguments()
    {
        return array(
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => HF_TEST_SVN_EXAMPLE,
                    'user'  => 'testuser12',
                    'hook'  => 'start-commit',
                ), true
            ),
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => HF_TEST_SVN_EXAMPLE,
                    'user'  => 'testuser',
                    'hook'  => 'start-commit',
                ), true
            ),
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => HF_TEST_SVN_EXAMPLE,
                    'user'  => '',
                    'hook'  => 'start-commit',
                ), false
            ),
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => '',
                    'user'  => 'testuser',
                    'hook'  => 'start-commit',
                ), false
            ),
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => '/x/x/svn',
                    'user'  => 'testuser',
                    'hook'  => 'start-commit',
                ), false
            ),
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => HF_TEST_SVN_EXAMPLE,
                    'user'  => 'testuser',
                    'hook'  => '',
                ), false
            ),
            array(
                array(
                    'path'  => '/var/local/svn/hooks/Hook',
                    'repos' => HF_TEST_SVN_EXAMPLE,
                    'user'  => 'testuser',
                    'hook'  => 'startcommit',
                ), false
            ),
            array(
                array(
                    'path'  => '',
                    'repos' => '',
                    'user'  => '',
                    'hook'  => '',
                ), false
            )
        );
    }

    /**
     * Test for arguments check.
     * @param array   $aData     Test data.
     * @param boolean $bExpected Expected Assert.
     * @return void
     * @author       Alexander Zimmermann <alex@zimmemann.com>
     * @dataProvider getStartArguments
     */
    public function testStartArguments(array $aData, $bExpected)
    {
        $aData = array(
            $aData['path'],
            $aData['repos'],
            $aData['user'],
            $aData['hook']
        );

        $oArguments = new Arguments($aData);

        $this->assertEquals($bExpected, $oArguments->argumentsOk(), $oArguments->getError());
    }
}
