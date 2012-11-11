<?php
/**
 * Mailing Test.
 * @category   Tests
 * @package    Listener
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Listener\Post;

use Hook\Commit\Data\Info;
use Hook\Commit\Data\Object;

use Example\Post\Mailing;

require_once __DIR__ . '/../../../Bootstrap.php';

require_once __DIR__ . '/../../../../Repositories/Example/Post/Mailing.php';

/**
 * Mailing Test.
 * @category   Tests
 * @package    Listener
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class MailingTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test object for Mailing.
	 * @var Mailing
	 */
	private $oMailingListener;

	/**
	 * SetUp operations.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oMailingListener = new Mailing();
	} // function

	/**
	 * Test that object was created.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testObject()
	{
		$sClass = get_class($this->oMailingListener);
		$this->assertEquals('Example\Post\Mailing', $sClass);
	} // function

	/**
	 * Test format of mail.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMailBodyI()
	{
		// "Mock" Info- Object.
		$sUser = 'duchess';
		$sDate = '2008-12-30 14:56:23';
		$sText = '* Test comment for the Unit Tests.';

		$oInfo = new Info('666-1', 666, $sUser, $sDate, $sText);

		// Test data for files.
		$aParams = array(
					'txn'    => '',
					'rev'    => 666,
					'action' => '',
					'item'   => '',
					'real'   => '',
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array(),
					'info'   => $oInfo
				   );

		// Create file objects.
		$aObjects = array();

		$aParams['action'] = 'U';
		$aParams['item']   = 'hookframework/trunk/Core/Commit/Base.php';
		$aParams['real']   = 'Core/Commit/Base.php';
		$aObjects[]        = new Object($aParams);

		$aParams['action'] = 'U';
		$aParams['item']   = 'hookframework/trunk/Core/Commit/Object.php';
		$aParams['real']   = 'Core/Commit/Object.php';
		$aObjects[]        = new Object($aParams);

		$aParams['action'] = 'A';
		$aParams['item']   = 'hookframework/trunk/doc/hooktemplates/';
		$aParams['real']   = 'doc/hooktemplates/';
		$aParams['isdir']  = true;
		$aObjects[]        = new Object($aParams);

		$aParams['action'] = 'A';
		$aParams['item']   = 'hookframework/trunk/doc/hooktemplates/pre-commit';
		$aParams['real']   = 'doc/hooktemplates/pre-commit';
		$aParams['isdir']  = false;
		$aObjects[]        = new Object($aParams);

		$aParams['action'] = 'D';
		$aParams['item']   = 'hookframework/trunk/tmp//newfolder/testfile.txt';
		$aParams['real']   = 'tmp//newfolder/testfile.txt';
		$aParams['isdir']  = false;
		$aObjects[]        = new Object($aParams);

		$aParams['action'] = 'D';
		$aParams['item']   = 'hookframework/trunk/tmp//newfolder/';
		$aParams['real']   = 'tmp//newfolder/';
		$aParams['isdir']  = true;
		$aObjects[]        = new Object($aParams);

		$oInfo->setObjects($aObjects);

		$sMail = $this->oMailingListener->processAction($oInfo);

		$sDir = 'hookframework/trunk/';

		$sExpected  = 'Date Time : 2008-12-30 14:56:23' . "\n\n";
		$sExpected .= 'User      : duchess' . "\n\n";
		$sExpected .= 'Comment   : * Test comment for the Unit Tests.' . "\n\n";

		$sExpected .= str_repeat('=', 80) . "\n";
		$sExpected .= 'Directories, Fileinformations:' . "\n";
		$sExpected .= str_repeat('-', 40) . "\n";
		$sExpected .= 'Added   : 2' . "\n";
		$sExpected .= 'Updated : 2' . "\n";
		$sExpected .= 'Deleted : 2' . "\n";
		$sExpected .= "\n";
		$sExpected .= $sDir . 'Core/Commit/Base.php (updated)' . "\n";
		$sExpected .= $sDir . 'Core/Commit/Object.php (updated)' . "\n";
		$sExpected .= $sDir . 'doc/hooktemplates/ (new)' . "\n";
		$sExpected .= $sDir . 'doc/hooktemplates/pre-commit (new)' . "\n";
		$sExpected .= $sDir . 'tmp//newfolder/testfile.txt (deleted)' . "\n";
		$sExpected .= $sDir . 'tmp//newfolder/ (deleted)' . "\n";

		$this->assertEquals($sExpected, $sMail);
	} // function

	/**
	 * Test the format of the mail.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMailBodyII()
	{
		// "Mock" Info- Object.
		$sUser = 'duchess';
		$sDate = '2008-12-30 14:56:23';
		$sText = '* Test comment for the Unit Tests.';

		$oInfo = new Info('666-1', 666, $sUser, $sDate, $sText);

		// Test data for files.
		$aPath[] = 'hookframework/trunk/Core/Commit/Base.php';
		$aPath[] = 'hookframework/trunk/Core/Commit/Object.php';
		$aReal[] = 'hookframework/trunk/Core/Commit/Base.php';
		$aReal[] = 'hookframework/trunk/Core/Commit/Object.php';


		// Create file objects.
		$aParams = array(
					'txn'    => '',
					'rev'    => 666,
					'action' => 'U',
					'item'   => $aPath[0],
					'real'   => $aReal[0],
					'isdir'  => false,
					'props'  => array(),
					'lines'  => array(),
					'info'   => $oInfo
				   );

		$aObjects   = array();
		$aObjects[] = new Object($aParams);

		$aParams['item'] = $aPath[1];
		$aParams['real'] = $aReal[1];
		$aObjects[]      = new Object($aParams);

		$oInfo->setObjects($aObjects);

		$sMail = $this->oMailingListener->processAction($oInfo);

		$sDir = 'hookframework/trunk/';

		$sExpected  = 'Date Time : 2008-12-30 14:56:23' . "\n\n";
		$sExpected .= 'User      : duchess' . "\n\n";
		$sExpected .= 'Comment   : * Test comment for the Unit Tests.' . "\n\n";

		$sExpected .= str_repeat('=', 80) . "\n";
		$sExpected .= 'Directories, Fileinformations:' . "\n";
		$sExpected .= str_repeat('-', 40) . "\n";
		$sExpected .= 'Updated : 2' . "\n";
		$sExpected .= "\n";
		$sExpected .= $sDir . 'Core/Commit/Base.php (updated)' . "\n";
		$sExpected .= $sDir . 'Core/Commit/Object.php (updated)' . "\n";

		$this->assertEquals($sExpected, $sMail);
	} // function
} // class
