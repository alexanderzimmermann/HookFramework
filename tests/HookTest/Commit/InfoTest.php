<?php
/**
 * Commit info tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Core\Commit;

use Hook\Commit\Data\Info;
use Hook\Commit\Data\Object;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Commit info tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class InfoTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Commit Info Object.
	 * @var Info
	 */
	private $oInfo;

	/**
	 * Set up method.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$sUser = 'Mila';
		$sDate = '2008-12-30 12:34:56';
		$sMsg  = '* A test message for the tests';

		$this->oInfo = new Info('74-1', 74, $sUser, $sDate, $sMsg);
	} // function

	/**
	 * Test if the user is returned correctly.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetUser()
	{
		$sExpected = 'Mila';
		$this->assertEquals($sExpected, $this->oInfo->getUser());
	} // function

	/**
	 * Test for correct return of date time.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetDateTime()
	{
		$sExpected = '2008-12-30 12:34:56';
		$this->assertEquals($sExpected, $this->oInfo->getDateTime());
	} // function

	/**
	 * Test return text message of the commit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetMessage()
	{
		$sExpected = '* A test message for the tests';
		$this->assertEquals($sExpected, $this->oInfo->getMessage());
	} // function

	/**
	 * Test set the list of object of the commit.
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
					'real'   => '/path/to/file',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array(),
					'info'   => $this->oInfo
				   );

		$oObject = new Object($aParams);

		$this->oInfo->setObjects(array($oObject));

		$aObjects = $this->oInfo->getObjects();

		$this->assertTrue(is_array($aObjects), 'no array');
		$this->assertEquals(1, count($aObjects), 'count aObjects not 1');

		$this->assertEquals('/path/to/file', $aObjects[0]->getObjectPath(), 'path wrong');
		$this->assertEquals('74-1', $aObjects[0]->getTransaction(), 'txn not 74');
		$this->assertEquals(74, $aObjects[0]->getRevision(), 'rev not 74');
	} // function
} // class