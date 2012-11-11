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

namespace HookTest\Core\Commit;

use Hook\Commit\Data\Info;
use Hook\Commit\Data\Object;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Commit object tests.
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
class ObjectTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Commit info object.
	 * @var Info
	 */
	private $oInfo;

	/**
	 * Commit object.
	 * @var Object
	 */
	private $oObject;

	/**
	 * Set up method.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function setUp()
	{
		$sUser = 'Lizzy';
		$sDate = '2008-12-30 12:34:56';
		$sMsg  = '* Eine Testnachricht fuer die Tests';

		$this->oInfo = new Info('74-1', 74, $sUser, $sDate, $sMsg);

		$aParams = array(
					'txn'    => '74-1',
					'rev'    => 74,
					'action' => 'U',
					'item'   => '/path/to/file.txt',
					'real'   => '/path/to/file.txt',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array(),
					'info'   => $this->oInfo
				   );

		$this->oObject = new Object($aParams);
	} // function

	/**
	 * Test return get action.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetAction()
	{
		$this->assertEquals('U', $this->oObject->getAction());
	} // function

	/**
	 * Test get isDir.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetIsDir()
	{
		$this->assertFalse($this->oObject->getIsDir());
	} // function

	/**
	 * Test return object path of file.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetObjectPath()
	{
		$sPath = '/path/to/file.txt';
		$this->assertEquals($sPath, $this->oObject->getObjectPath());
	} // function

	/**
	 * Test of return commit info object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetInfo()
	{
		$oObject = $this->oObject->getInfo();
		$this->assertEquals($this->oInfo, $oObject);
	} // function

	/**
	 * Test return temporarily path of file.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetTmpObjectPath()
	{
		$sPath = '/tmp/74-1-_path_to_file.txt';
		$this->assertEquals($sPath, $this->oObject->getTmpObjectPath());
	} // function
} // class