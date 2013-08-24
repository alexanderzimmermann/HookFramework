<?php
/**
 * Commit Base Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Core\Commit;

use Hook\Commit\Base;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Commit Base Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Commit Base Object.
	 * @var Base
	 */
	private $oBase;

	/**
	 * Set Up Method.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function setUp()
	{
		$this->oBase = new Base('74-1', 74);
	}

	/**
	 * Check correct transaction number.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetTransaction()
	{
		$this->assertEquals('74-1', $this->oBase->getTransaction());
	}

	/**
	 * Check correct revision number.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetRevision()
	{
		$this->assertEquals(74, $this->oBase->getRevision());
	}

	/**
	 * Test for added error lines.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testAddError()
	{
		$aLines   = array();
		$aLines[] = 'Eine Fehlermeldung.';
		$aLines[] = 'Eine weitere Fehlermeldung.';

		$this->oBase->addError($aLines[0]);
		$this->oBase->addError($aLines[1]);

		$aError = $this->oBase->getErrorLines();

		$this->assertTrue(is_array($aError), 'no array');
		$this->assertFalse(empty($aError), 'array aError empty');
		$this->assertEquals($aLines, $aError, 'aError does not eqal aLines.');
	}

	/**
	 * Test for adding multiple error lines.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testAddErrorLines()
	{
		$aLines   = array();
		$aLines[] = 'Eine Fehlermeldung.';
		$aLines[] = 'Eine weitere Fehlermeldung.';

		$this->oBase->addErrorLines($aLines);

		$aLines   = array();
		$aLines[] = 'Eine Fehlermeldung.';
		$aLines[] = 'Eine weitere Fehlermeldung.';

		$this->oBase->addErrorLines($aLines);

		$aError = $this->oBase->getErrorLines();

		$this->assertTrue(is_array($aError), 'no array');
		$this->assertFalse(empty($aError), 'array aError empty');

		$aLines[] = 'Eine Fehlermeldung.';
		$aLines[] = 'Eine weitere Fehlermeldung.';

		$this->assertEquals($aLines, $aError, 'aError does not eqal aLines.');
	}
}