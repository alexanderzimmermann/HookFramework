<?php
/**
 * Repository-Object Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.1
 */

require_once dirname(__FILE__) . '/../TestHelper.php';
require_once 'Core/Arguments.php';
require_once 'Core/Repository.php';

/**
 * Repository-Object Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.1
 */
class RepositoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testInit()
	{
		$aFunctions = array(
					   'getRepository', 'getMainType', 'getSubActions'
					  );

		$aArguments = array(
					   array(
						0 => '/var/local/svn/hooks/Hook',
						1 => dirname(__FILE__) . '/svn',
						2 => 'testuser12',
						3 => 'pre-commit'
					   )
					  );

		$oArguments = $this->getMock('Arguments', $aFunctions, $aArguments);

		$oArguments->expects($this->any())
				   ->method('getRepository')
				   ->will($this->returnValue('test'));

		$oArguments->expects($this->any())
				   ->method('getMainType')
				   ->will($this->returnValue('pre'));

		$oArguments->expects($this->any())
				   ->method('getSubActions')
				   ->will($this->returnValue(array('commit')));

		$sPath = dirname(__FILE__) . '/_files/Repositories/';

		$oRepos = new Repository($oArguments);
		$oRepos->setPath($sPath);
		$oRepos->init();

		// Tests.
		$this->assertTrue($oRepos->hasLogfile(), 'Logfile is not available');

		$sExpected = dirname(__FILE__)
				   . '/_files/Repositories/test/logs/common.log';
		$this->assertSame($sExpected, $oRepos->getLogfile(), 'Logfile false');
	} // function

	/**
	 * Test that the correct listener are found.
	 * @return void
	 * @author Alexander Zimmermann <alexander.zimmermann@twt.de>
	 */
	public function testGetListener()
	{
		$aFunctions = array(
					   'getRepository', 'getMainType', 'getSubActions'
					  );

		$aArguments = array(
					   array(
						0 => '/var/local/svn/hooks/Hook',
						1 => dirname(__FILE__) . '/svn',
						2 => 'testuser12',
						3 => 'pre-commit'
					   )
					  );

		$oArguments = $this->getMock('Arguments', $aFunctions, $aArguments);

		$oArguments->expects($this->any())
				   ->method('getRepository')
				   ->will($this->returnValue('test'));

		$oArguments->expects($this->any())
				   ->method('getMainType')
				   ->will($this->returnValue('pre'));

		$oArguments->expects($this->any())
				   ->method('getSubActions')
				   ->will($this->returnValue(array('commit')));

		$sPath = dirname(__FILE__) . '/_files/Repositories/';

		$oRepos = new Repository($oArguments);
		$oRepos->setPath($sPath);
		$oRepos->init();

		$aExpected = array();
		$aActual   = $oRepos->getListener();

		$this->assertTrue(isset($aActual['info']), 'Index info missing');
		$this->assertTrue(isset($aActual['object']), 'Index object missing');
	} // function
} // class
