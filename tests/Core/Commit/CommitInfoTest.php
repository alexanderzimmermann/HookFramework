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

require_once 'Core/Commit/CommitObject.php';
require_once 'Core/Commit/CommitInfo.php';

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
class CommitInfoTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Commit Info Objekt.
	 * @var CommitInfo
	 */
	private $oCommitInfo;

	/**
	 * Set Up Methode.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$sUser = 'Benutzer';
		$sDate = '2008-12-30 12:34:56';
		$sMsg  = '* Eine Testnachricht fuer die Tests';

		$this->oCommitInfo = new CommitInfo('74-1', 74, $sUser, $sDate, $sMsg);
	} // function

	/**
	 * Testen ob der Benutzer korrekt zurueck gegeben wird.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetUser()
	{
		$sExpected = 'Benutzer';
		$this->assertEquals($sExpected, $this->oCommitInfo->getUser());
	} // function

	/**
	 * Test ob Datum Zeit korrekt zurueck gegeben wird.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetDateTime()
	{
		$sExpected = '2008-12-30 12:34:56';
		$this->assertEquals($sExpected, $this->oCommitInfo->getDateTime());
	} // function

	/**
	 * Textbemerkung zum Commit zurueck geben.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetMessage()
	{
		$sExpected = '* Eine Testnachricht fuer die Tests';
		$this->assertEquals($sExpected, $this->oCommitInfo->getMessage());
	} // function

	/**
	 * Setzen der Liste der Objekte des Commits.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testSetGetObjects()
	{
		$aParams = array(
					'txn'    => '74-1',
					'rev'    => 74,
					'action' => 'U',
					'item'   => '/path/to/file',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array(),
					'info'   => $this->oCommitInfo
				   );

		$oObject = new CommitObject($aParams);

		$this->oCommitInfo->setObjects(array($oObject));

		$aObjects = $this->oCommitInfo->getObjects();

		$this->assertTrue(is_array($aObjects), 'no array');
		$this->assertEquals(1, count($aObjects), 'count aObjects not 1');

		$this->assertEquals('/path/to/file', $aObjects[0]->getObjectPath(), 'path wrong');
		$this->assertEquals('74-1', $aObjects[0]->getTransaction(), 'txn not 74');
		$this->assertEquals(74, $aObjects[0]->getRevision(), 'rev not 74');
	} // function
} // class