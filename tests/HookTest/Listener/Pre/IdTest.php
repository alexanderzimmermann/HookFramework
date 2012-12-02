<?php
/**
 * Property Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Listener\Pre;

use Hook\Commit\Data\Object;

use Example\Pre\Id;

require_once __DIR__ . '/../../../Bootstrap.php';

require_once __DIR__ . '/../../../../Repositories/Example/Pre/Id.php';

/**
 * Property Tests.
 * @category   Tests
 * @package    Listener
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class IdTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test object Property Listener.
	 * @var Id
	 */
	private $oIdListener;

	/**
	 * SetUp operations.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oIdListener = new Id();
	} // function

	/**
	 * Simple test the object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testObject()
	{
		$this->assertEquals('Example\Pre\Id', get_class($this->oIdListener));
	} // function

	/**
	 * Test the listener that everything is ok.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @ddepends testObject
	 */
	public function testAddActionOk()
	{
		// Mock the property object.
		$oProperty = $this->getMock('Hook\Commit\Data\Diff\Property', array(), array('svn:keywords'));
		$oProperty->expects($this->any())
				  ->method('getOldValue')
				  ->will($this->returnValue(''));

		$oProperty->expects($this->any())
				  ->method('getNewValue')
				  ->will($this->returnValue('Id'));

		$aParams = array(
					'txn'    => '666-1',
					'rev'    => 666,
					'action' => 'A',
					'item'   => 'file.php',
					'real'   => 'file.php',
					'isdir'  => false,
					'props'  => array('svn:keywords' => $oProperty),
					'lines'  => null,
					'info'   => null
				   );

		$oObject = new Object($aParams);

		$this->oIdListener->processAction($oObject);

		// Check.
		$aErrors = $oObject->getErrorLines();

		$this->assertTrue(empty($aErrors));
	} // function

	/**
	 * Test when the svn:keywords tag is not set.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @ddepends testObject
	 */
	public function testAddActionAndNoKeyword()
	{
		// Mock the property object.
		$oProperty = $this->getMock('Hook\Commit\Data\Diff\Property', array(), array('tag'));
		$oProperty->expects($this->any())
				  ->method('getOldValue')
				  ->will($this->returnValue(''));

		$oProperty->expects($this->any())
				  ->method('getNewValue')
				  ->will($this->returnValue(''));

		$aParams = array(
					'txn'    => '666-1',
					'rev'    => 666,
					'action' => 'A',
					'item'   => 'file.php',
					'real'   => 'file.php',
					'isdir'  => false,
					'props'  => array('tag' => $oProperty),
					'lines'  => null,
					'info'   => null
				   );

		$oObject = new Object($aParams);

		$this->oIdListener->processAction($oObject);

		// Check.
		$aErrors = $oObject->getErrorLines();

		$this->assertSame(1, count($aErrors), 'Error count not 1.');
		$sMsg = 'Please add the "svn:keywords - Id" tag to the file.';
		$this->assertSame($sMsg, $aErrors[0], 'Error wrong.');
	} // function

	/**
	 * Test that the svn:keywords is set but not with Id Value.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @ddepends testObject
	 */
	public function testAddActionAndKeyword()
	{
		// Mock the property object.
		$oProperty = $this->getMock('Hook\Commit\Data\Diff\Property', array(), array('svn:keywords'));
		$oProperty->expects($this->any())
				  ->method('getOldValue')
				  ->will($this->returnValue(''));

		$oProperty->expects($this->any())
				  ->method('getNewValue')
				  ->will($this->returnValue(''));

		$aParams = array(
					'txn'    => '666-1',
					'rev'    => 666,
					'action' => 'A',
					'item'   => 'file.php',
					'real'   => 'file.php',
					'isdir'  => false,
					'props'  => array('svn:keywords' => $oProperty),
					'lines'  => null,
					'info'   => null
				   );

		$oObject = new Object($aParams);

		$this->oIdListener->processAction($oObject);

		// Check.
		$aErrors = $oObject->getErrorLines();

		$this->assertSame(1, count($aErrors), 'Error count not 1.');
		$sMsg = 'Please add the "Id" value to the svn:keywords tag.';
		$this->assertSame($sMsg, $aErrors[0], 'Error wrong.');
	} // function




	/**
	 * Test the listener that everything is ok.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @ddepends testObject
	 */
	public function testUpdateActionOk()
	{
		// Mock the property object.
		$oProperty = $this->getMock('Hook\Commit\Data\Diff\Property', array(), array('svn:keywords'));
		$oProperty->expects($this->any())
				  ->method('getOldValue')
				  ->will($this->returnValue(''));

		$oProperty->expects($this->any())
				  ->method('getNewValue')
				  ->will($this->returnValue('Id'));

		$aParams = array(
					'txn'    => '666-1',
					'rev'    => 666,
					'action' => 'U',
					'item'   => 'file.php',
					'real'   => 'file.php',
					'isdir'  => false,
					'props'  => array('svn:keywords' => $oProperty),
					'lines'  => null,
					'info'   => null
				   );

		$oObject = new Object($aParams);

		$this->oIdListener->processAction($oObject);

		// Check.
		$aErrors = $oObject->getErrorLines();

		$this->assertTrue(empty($aErrors));
	} // function

	/**
	 * Test when the svn:keywords tag is not set.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @ddepends testObject
	 */
	public function testUpdateActionAndNoKeyword()
	{
		// Mock the property object.
		$oProperty = $this->getMock('Hook\Commit\Data\Diff\Property', array(), array('tag'));
		$oProperty->expects($this->any())
				  ->method('getOldValue')
				  ->will($this->returnValue(''));

		$oProperty->expects($this->any())
				  ->method('getNewValue')
				  ->will($this->returnValue(''));

		$aParams = array(
					'txn'    => '666-1',
					'rev'    => 666,
					'action' => 'U',
					'item'   => 'file.php',
					'real'   => 'file.php',
					'isdir'  => false,
					'props'  => array('tag' => $oProperty),
					'lines'  => null,
					'info'   => null
				   );

		$oObject = new Object($aParams);

		$this->oIdListener->processAction($oObject);

		// Check.
		$aErrors = $oObject->getErrorLines();

		$this->assertTrue(empty($aErrors));
	} // function

	/**
	 * Test that the svn:keywords is set but not with Id Value.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @ddepends testObject
	 */
	public function testUpdateActionAndKeyword()
	{
		// Mock the property object.
		$oProperty = $this->getMock('Hook\Commit\Data\Diff\Property', array(), array('svn:keywords'));
		$oProperty->expects($this->any())
				  ->method('getOldValue')
				  ->will($this->returnValue(''));

		$oProperty->expects($this->any())
				  ->method('getNewValue')
				  ->will($this->returnValue(''));

		$aParams = array(
					'txn'    => '666-1',
					'rev'    => 666,
					'action' => 'U',
					'item'   => 'file.php',
					'real'   => 'file.php',
					'isdir'  => false,
					'props'  => array('svn:keywords' => $oProperty),
					'lines'  => null,
					'info'   => null
				   );

		$oObject = new Object($aParams);

		$this->oIdListener->processAction($oObject);

		// Check.
		$aErrors = $oObject->getErrorLines();

		$this->assertSame(1, count($aErrors), 'Error count not 1.');
		$sMsg = 'Do not delete the "Id" tag of the file.';
		$this->assertSame($sMsg, $aErrors[0], 'Error wrong.');
	} // function
} // class
