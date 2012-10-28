<?php
/**
 * Commit object tests.
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

namespace CoreTest\Commit;

use Core\Commit\CommitInfo;
use Core\Commit\CommitObject;

require_once __DIR__ . '/../../Bootstrap.php';

require_once 'Core/Commit/CommitObject.php';

// CommitInfo.
require_once 'Core/Commit/CommitInfo.php';

/**
 * Commit object tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class CommitObjectTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Commit info object.
	 * @var CommitInfo
	 */
	private $oCommitInfo;

	/**
	 * Commit object.
	 * @var CommitObject
	 */
	private $oCommitObject;

	/**
	 * Set up method.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function setUp()
	{
		$sUser = 'Benutzer';
		$sDate = '2008-12-30 12:34:56';
		$sMsg  = '* Eine Testnachricht fuer die Tests';

		$this->oCommitInfo = new CommitInfo('74-1', 74, $sUser, $sDate, $sMsg);

		$aParams = array(
					'txn'    => '74-1',
					'rev'    => 74,
					'action' => 'U',
					'item'   => '/path/to/file.txt',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array(),
					'info'   => $this->oCommitInfo
				   );

		$this->oCommitObject = new CommitObject($aParams);
	} // function

	/**
	 * Test return get action.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetAction()
	{
		$this->assertEquals('U', $this->oCommitObject->getAction());
	} // function

	/**
	 * Test get isDir.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetIsDir()
	{
		$this->assertFalse($this->oCommitObject->getIsDir());
	} // function

	/**
	 * Test return object path of file.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetObjectPath()
	{
		$sPath = '/path/to/file.txt';
		$this->assertEquals($sPath, $this->oCommitObject->getObjectPath());
	} // function

	/**
	 * Test of return commit info object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetCommitInfo()
	{
		$oObject = $this->oCommitObject->getCommitInfo();
		$this->assertEquals($this->oCommitInfo, $oObject);
	} // function

	/**
	 * Test return temporarily path of file.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetTmpObjectPath()
	{
		$sPath = '/tmp/74-1-_path_to_file.txt';
		$this->assertEquals($sPath, $this->oCommitObject->getTmpObjectPath());
	} // function
} // class