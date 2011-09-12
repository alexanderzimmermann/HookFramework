<?php
/**
 * Argument Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Core/Commit/CommitBase.php';

/**
 * Argument Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class CommitBaseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Commit Base Objekt.
	 * @var CommitBase
	 */
	private $oCommitBase;

	/**
	 * Set Up Methode.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function setUp()
	{
		$this->oCommitBase = new CommitBase('74-1', 74);
	} // function

	/**
	 * Korrekte Transaktionsnummer pruefen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetTransaction()
	{
		$this->assertEquals('74-1', $this->oCommitBase->getTransaction());
	} // function

	/**
	 * Korrekte Revisionsnummer pruefen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetRevision()
	{
		$this->assertEquals(74, $this->oCommitBase->getRevision());
	} // function

	/**
	 * Test fuer Fehlerzeilen hinzufuegen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testAddError()
	{
		$aLines   = array();
		$aLines[] = 'Eine Fehlermeldung.';
		$aLines[] = 'Eine weitere Fehlermeldung.';

		$this->oCommitBase->addError($aLines[0]);
		$this->oCommitBase->addError($aLines[1]);

		$aError = $this->oCommitBase->getErrorLines();

		$this->assertTrue(is_array($aError), 'no array');
		$this->assertFalse(empty($aError), 'array aError empty');
		$this->assertEquals($aLines, $aError, 'aError does not eqal aLines.');
	} // function

	/**
	 * Test fuer mehrere Fehlerzeilen hinzufuegen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testaddErrorLines()
	{
		$aLines   = array();
		$aLines[] = 'Eine Fehlermeldung.';
		$aLines[] = 'Eine weitere Fehlermeldung.';

		$this->oCommitBase->addErrorLines($aLines);

		$aLines   = array();
		$aLines[] = 'Eine Fehlermeldung.';
		$aLines[] = 'Eine weitere Fehlermeldung.';

		$this->oCommitBase->addErrorLines($aLines);

		$aError = $this->oCommitBase->getErrorLines();

		$this->assertTrue(is_array($aError), 'no array');
		$this->assertFalse(empty($aError), 'array aError empty');

		$aLines[] = 'Eine Fehlermeldung.';
		$aLines[] = 'Eine weitere Fehlermeldung.';

		$this->assertEquals($aLines, $aError, 'aError does not eqal aLines.');
	} // function
} // class