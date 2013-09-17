<?php
/**
 * Diff object tests.
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

use Hook\Commit\Diff\Diff;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Diff object tests.
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
class DiffTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Diff object to test.
     * @var Diff
     */
    protected $oFixture;

    /**
     * Setup.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setUp()
    {
        $this->oFixture = new Diff;
    }

    /**
     * Test set old length and get it as is.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSetAndGetOldLength()
    {
        $this->oFixture->setOldLength(42);
        $this->assertSame(42, $this->oFixture->getOldLength());
    }

    /**
     * Test set new length and get it as is.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSetAndGetNewLength()
    {
        $this->oFixture->setNewLength(42);
        $this->assertSame(42, $this->oFixture->getNewLength());
    }
}
