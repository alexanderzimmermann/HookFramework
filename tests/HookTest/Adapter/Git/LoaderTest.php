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

namespace HookTest\Adapter\Git;

use Hook\Adapter\Git\Arguments;
use Hook\Adapter\Git\Loader;
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
 * @since      Class available since Release 1.0.0
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
        // Main type usually is pre, post and start but here Failures to check listener.
        $oArguments = $this->getMock('Hook\Adapter\Git\Arguments', array(), array(), '', false);

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
        $this->assertFalse(isset($aListener['info']), 'Info Element is not set in $aListener.');
        $this->assertTrue(isset($aListener['object']), 'Object Element is not set in $aListener.');

        // Object listener test.
        $this->assertEquals(1, count($aListener['object']), 'Info Listener count not 1.');
        $sExpectedName = 'Test Object Listener Ok.';
        $sActualName   = $aListener['object'][0]->getListenerName();
        $this->assertEquals($sExpectedName, $sActualName, 'Object Listener Name not correct!');
    }
}
