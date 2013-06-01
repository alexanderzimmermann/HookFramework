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
     * Setup
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUp()
    {
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
}
