<?php
/**
 * Lines object tests.
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

use Hook\Commit\Diff\Lines;
use Hook\Commit\Diff\Diff;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Lines object tests.
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
class LinesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Lines object to test.
     * @var Lines
     */
    protected $oFixture;

    /**
     * Setup.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setUp()
    {
        $this->oFixture = new Lines;
    }

    /**
     * Test set diff info object and get as is.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSetAndGetInfo()
    {
        $oDiff = new Diff();
        $this->oFixture->setInfo($oDiff);
        $this->assertSame($oDiff, $this->oFixture->getInfo());
    }

    /**
     * Test set the raw lines and leave as is.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSetAndGetRawLines()
    {
        $aLines = array('Some diff lines');
        $this->oFixture->setRawLines($aLines);
        $this->assertSame($aLines, $this->oFixture->getRawLines());
    }

    /**
     * Test set the raw lines and leave as is.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSetAndGetOldLines()
    {
        $aLines = array('Some diff lines');
        $this->oFixture->setOldLines($aLines);
        $this->assertSame($aLines, $this->oFixture->getOldLines());
    }
}
