<?php
/**
 * Property object tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace HookTest\Adapter\Git;

use Hook\Commit\Diff\Property;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Property object tests.
 * @category   Tests
 * @package    Commit
 * @subpackage Diff
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class PropertyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Property object to test.
     * @var Property
     */
    protected $oFixture;

    /**
     * Setup.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setUp()
    {
        $this->oFixture = new Property('test');
    }

    /**
     * Test get property name from constructor as is.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetPropertyName()
    {
        $this->assertSame('test', $this->oFixture->getPropertyName());
    }
}
