<?php
/**
 * Repository-Object Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.1
 */

namespace CoreTest;

use Core\Arguments;
use Core\Repository;

require_once __DIR__ . '/../Bootstrap.php';

/**
 * Repository-Object Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 2.0.0
 */
class RepositoryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testInit()
	{
		$aFunctions = array(
					   'getRepositoryName', 'getMainType', 'getSubActions'
					  );

		$aArguments = array(
			array(
				0 => '/var/local/svn/hooks/Hook',
				1 => TEST_SVN_EXAMPLE,
				2 => 'testuser12',
				3 => 'pre-commit'
			)
		);

		$oArguments = $this->getMock('Core\Arguments', $aFunctions, $aArguments);

		$oArguments->expects($this->any())
				   ->method('getRepositoryName')
				   ->will($this->returnValue('test'));

		$oArguments->expects($this->any())
				   ->method('getMainType')
				   ->will($this->returnValue('pre'));

		$oArguments->expects($this->any())
				   ->method('getSubActions')
				   ->will($this->returnValue(array('commit')));

		$sPath = __DIR__ . '/../../Repositories/';

		$oRepos = new Repository($oArguments);
		$oRepos->setPath($sPath);
		$oRepos->init();

		// Tests.
		$this->assertTrue($oRepos->hasLogfile(), 'Logfile is not available');

		$sExpected = __DIR__ . '/../../Repositories/Example/logs/common.log';
		$this->assertSame($sExpected, $oRepos->getLogfile(), 'Logfile false');
	} // function

	/**
	 * Test that the correct listeners are found.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testGetListener()
	{
		$aFunctions = array(
					   'getRepositoryName', 'getMainType', 'getSubActions'
					  );

		$aArguments = array(
					   array(
						0 => '/var/local/svn/hooks/Hook',
						1 => TEST_SVN_EXAMPLE,
						2 => 'testuser12',
						3 => 'pre-commit'
					   )
					  );

		$oArguments = $this->getMock('Core\Arguments', $aFunctions, $aArguments);

		$oArguments->expects($this->any())
				   ->method('getRepositoryName')
				   ->will($this->returnValue('Example'));

		$oArguments->expects($this->any())
				   ->method('getMainType')
				   ->will($this->returnValue('pre'));

		$oArguments->expects($this->any())
				   ->method('getSubActions')
				   ->will($this->returnValue(array('commit')));

		$sPath = __DIR__ . '/../../Repositories/';

		$oRepos = new Repository($oArguments);
		$oRepos->setPath($sPath);
		$oRepos->init();

		$aActual = $oRepos->getListener();

		$this->assertTrue(isset($aActual['info']), 'Index info missing');
		$this->assertTrue(isset($aActual['object']), 'Index object missing');
	} // function
} // class
