<?php
/**
 * Tests that the listeners are loaded correctly.
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
use Hook\Adapter\Svn\Loader;
use Hook\Core\Config;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Tests that the listeners are loaded correctly.
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
class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the handle of errors in listener classes.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testLoaderFailures()
    {
        $aFunctions = array(
            'getRepositoryName', 'getMainType', 'getSubActions'
        );

        $aArguments = array(
            array(
                0 => '/var/local/svn/hooks/Hook',
                1 => HF_TEST_SVN_EXAMPLE,
                2 => 'Juliana',
                3 => 'pre-commit'
            )
        );

        // Main type usually is pre, post and start but here Failures to check listener.
        $oArguments = $this->getMock('Hook\Adapter\Svn\Arguments', $aFunctions, $aArguments);

        $oArguments->expects($this->any())
            ->method('getRepositoryName')
            ->will($this->returnValue('HookTest\\Listener'));

        $oArguments->expects($this->any())
            ->method('getMainType')
            ->will($this->returnValue('Failures'));

        $oArguments->expects($this->any())
            ->method('getSubActions')
            ->will($this->returnValue(array('commit')));

        // Just the instance.
        $oConfig = new Config;

        $sTestDir = HF_TEST_DIR . 'HookTest/Listener/';

        $oLoader = new Loader();
        $oLoader->setArguments($oArguments);
        $oLoader->setConfiguration($oConfig);
        $oLoader->setPath($sTestDir);
        $oLoader->init();

        $aListener = $oLoader->getListener();

        $this->assertTrue(is_array($aListener), '$aListener is not an array.');
        $this->assertTrue(isset($aListener['info']), 'Info Element is not set in $aListener.');
        $this->assertTrue(isset($aListener['object']), 'Object Element is not set in $aListener.');

        // Info listener test.
        $this->assertEquals(1, count($aListener['info']), 'Info Listener count not 1.');
        $sExpectedName = 'Test Info Listener Ok.';
        $sActualName   = $aListener['info'][0]->getListenerName();
        $this->assertEquals($sExpectedName, $sActualName, 'Info Listener Name not correct!');

        // Object listener test.
        $this->assertEquals(1, count($aListener['object']), 'Info Listener count not 1.');
        $sExpectedName = 'Test Object Listener Ok.';
        $sActualName   = $aListener['object'][0]->getListenerName();
        $this->assertEquals($sExpectedName, $sActualName, 'Object Listener Name not correct!');
    }

    /**
     * Data provider.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getArguments()
    {
        return array(
            array(
                array(
                    0        => '/var/local/svn/hooks/Hook',
                    1        => HF_TEST_SVN_EXAMPLE,
                    2        => '666-1',
                    3        => 'pre-commit'
                ),
                array(
                    'info'   => array(
                        'ExampleSvn\Pre\MessageStrict', 'ExampleSvn\Pre\Message',
                    ),
                    'object' => array(
                        'ExampleSvn\Pre\Style', 'ExampleSvn\Pre\Id', 'ExampleSvn\Pre\Syntax',
                        'ExampleSvn\Pre\StyleIncrement'
                    )
                )
            ),
            array(
                array(
                    0        => '/var/local/svn/hooks/Hook',
                    1        => HF_TEST_SVN_EXAMPLE,
                    2        => 666,
                    3        => 'post-commit'
                ),
                array(
                    'info'   => array('ExampleSvn\Post\Mailing'),
                    'object' => array('ExampleSvn\Post\Diff')
                )
            ),
            array(
                array(
                    0        => '/var/local/svn/hooks/Hook',
                    1        => HF_TEST_SVN_EXAMPLE,
                    2        => 'testuser12',
                    3        => 'start-commit'
                ),
                array(
                    'info'   => array('ExampleSvn\Start\Start'),
                    'object' => array()
                )
            )
        );
    }

    /**
     * Test the listener Loader.
     *
     * Simple test with the available listener of the framework.
     * @param array $aArguments Arguments.
     * @param array $aExpected  Test data.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     * @dataProvider getArguments
     */
    public function testLoaderObjects(array $aArguments, array $aExpected)
    {
        $oArguments = new Arguments($aArguments);
        $this->assertTrue($oArguments->argumentsOk(), 'Arguments not ok.');

        // Just the instance.
        $oConfig = new Config;
        $oConfig->loadConfigFile(__DIR__ . '/_files/test-config.ini');

        $oLoader = new Loader();
        $oLoader->setArguments($oArguments);
        $oLoader->setConfiguration($oConfig);
        $oLoader->setPath(HF_TEST_SVN_REPOSITORY . 'ExampleSvn/');

        $oLoader->init();
        $aListener = $oLoader->getListener();

        $this->assertFalse(empty($aListener), 'Listener empty.');

        if ((isset($aExpected['info']) === true) &&
            (empty($aExpected['info']) === false)) {

            $this->assertTrue(isset($aListener['info']), 'No info set.');

            $iInfoListener = count($aListener['info']);
            $this->assertEquals(count($aExpected['info']), $iInfoListener, 'Info count false.');

            // Class name should be the same.
            $sMsg = 'get_class info false, item ';
            $iMax = count($aListener['info']);
            for ($iFor = 0; $iFor < $iMax; $iFor++) {

                $sClassName = get_class($aListener['info'][$iFor]);
                $this->assertTrue(in_array($sClassName, $aExpected['info'], $sMsg . $iFor));
            }
        }

        if ((isset($aExpected['object']) === true) &&
            (empty($aExpected['object']) === false)) {
            $this->assertTrue(isset($aListener['object']), 'No object set.');

            $iListener = count($aListener['object']);
            $this->assertEquals(count($aExpected['object']), $iListener, 'Listener count false.');

            // Class name should be the same.
            $sMsg = 'get_class object false, item ';
            $iMax = count($aListener['object']);
            for ($iFor = 0; $iFor < $iMax; $iFor++) {

                $sClassName = get_class($aListener['object'][$iFor]);
                $this->assertTrue(in_array($sClassName, $aExpected['object'], $sMsg . $iFor));
            }
        }
    }
}
