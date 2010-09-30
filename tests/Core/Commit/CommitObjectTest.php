<?php
/**
 * Argument Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Core/Commit/CommitObject.php';

// CommitInfo.
require_once 'Core/Commit/CommitInfo.php';

/**
 * Argument Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class CommitObjectTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Commit Info Objekt.
	 * @var CommitInfo
	 */
	private $oCommitInfo;

	/**
	 * Commit Objekt.
	 * @var CommitObject
	 */
	private $oCommitObject;

	/**
	 * Set Up Methode.
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
	 * Aktion zurueck geben.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetAction()
	{
		$this->assertEquals('U', $this->oCommitObject->getAction());
	} // function

	/**
	 * Ist das Objekt ein Verzeichnis.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetIsDir()
	{
		$this->assertFalse($this->oCommitObject->getIsDir());
	} // function

	/**
	 * Pfad zu der Datei zurueck geben.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetObjectPath()
	{
		$sPath = '/path/to/file.txt';
		$this->assertEquals($sPath, $this->oCommitObject->getObjectPath());
	} // function

	/**
	 * Das Commit Info Objekt zurueck geben.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetCommitInfo()
	{
		$oObject = $this->oCommitObject->getCommitInfo();
		$this->assertEquals($this->oCommitInfo, $oObject);
	} // function

	/**
	 * Temporaeren Pfad zur Datei zurueck geben.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetTmpObjectPath()
	{
		$sPath = '/tmp/74-1-_path_to_file.txt';
		$this->assertEquals($sPath, $this->oCommitObject->getTmpObjectPath());
	} // function
} // class