<?php
/**
 * Listener parser tests.
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

namespace HookTest\Core;

use Hook\Core\Arguments;
use Hook\Listener\ListenerParser as Parser;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Listener parser tests.
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
class ListenerParserTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the handle of errors in listener classes.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testListenerParserFailures()
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

		// Main type usually is pre, post and start but here Failures to check listener.
		$oArguments = $this->getMock('Hook\Core\Arguments', $aFunctions, $aArguments);

		$oArguments->expects($this->any())
				   ->method('getRepositoryName')
				   ->will($this->returnValue('HookTest\\Listener'));

		$oArguments->expects($this->any())
				   ->method('getMainType')
				   ->will($this->returnValue('Failures'));

		$oArguments->expects($this->any())
				   ->method('getSubActions')
				   ->will($this->returnValue(array('commit')));

		$sTestDir = TEST_SVN_REPOSITORY;
		$sTestDir = __DIR__ . '/../Listener/';

		$oListenerParser = new Parser($oArguments);
		$oListenerParser->setPath($sTestDir);
		$oListenerParser->init();

		$aListener = $oListenerParser->getListener();

		$this->assertTrue(is_array($aListener), '$aListener is not an array.');
		$this->assertTrue(isset($aListener['info']), 'Info Element is not set in $aListener.');
		$this->assertTrue(isset($aListener['object']), 'Object Element is not set in $aListener.');

		// Info listener test.
		$this->assertEquals(1, count($aListener['info']), 'Info Listener count not 1.');
		$sExpectedName = 'Test Info Listener Ok.';
		$sActualName   = $aListener['info'][0]->getListenerName();
		$this->assertEquals($sExpectedName, $sActualName, 'Info Listener Name not correct!');

		// Object listener test.
		$this->assertEquals(1, count($aListener['object']), 'Info Listener count not 1.');
		$sExpectedName = 'Test Object Listener Ok.';
		$sActualName   = $aListener['object'][0]->getListenerName();
		$this->assertEquals($sExpectedName, $sActualName, 'Object Listener Name not correct!');
	} // function

	/**
	 * Data provider.
	 * @return array
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public function getArguments()
	{
		return array(
				array(
				 array(
				  0        => '/var/local/svn/hooks/Hook',
				  1        => TEST_SVN_EXAMPLE,
				  2        => '666-1',
				  3        => 'pre-commit'
				 ),
				 array(
				  'info'   => array(
							   'Example\Pre\MessageStrict', 'Example\Pre\Message',
							  ),
				  'object' => array(
							   'Example\Pre\Style', 'Example\Pre\Id', 'Example\Pre\Syntax'
							  )
				 )
				),
				array(
				 array(
				  0        => '/var/local/svn/hooks/Hook',
				  1        => TEST_SVN_EXAMPLE,
				  2        => 666,
				  3        => 'post-commit'
				 ),
				 array(
				  'info'   => array('Example\Post\Mailing'),
				  'object' => array('Example\Post\Diff')
				 )
				),
				array(
				 array(
				  0        => '/var/local/svn/hooks/Hook',
				  1        => TEST_SVN_EXAMPLE,
				  2        => 'testuser12',
				  3        => 'start-commit'
				 ),
				 array(
				  'info'   => array('Example\Start\Start'),
				  'object' => array()
				 )
				)
			   );
	} // function

	/**
	 * Test the listener parser.
	 *
	 * Simple test with the available listener of the framework.
	 * @param array $aArguments Arguments.
	 * @param array $aExpected  Test data.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @dataProvider getArguments
	 */
	public function testListenerParserObjects(array $aArguments, array $aExpected)
	{
		$oArguments = new Arguments($aArguments);
		$this->assertTrue($oArguments->argumentsOk(), 'Arguments not ok.');

		$oListenerParser = new Parser($oArguments);
		$oListenerParser->setPath(TEST_SVN_REPOSITORY . 'Example/');

		$oListenerParser->init();
		$aListener = $oListenerParser->getListener();

		$this->assertFalse(empty($aListener), 'Listener empty.');

		if ((isset($aExpected['info']) === true) &&
			(empty($aExpected['info']) === false))
		{
			$this->assertTrue(isset($aListener['info']), 'No info set.');

			$iInfoListener = count($aListener['info']);
			$this->assertEquals(count($aExpected['info']), $iInfoListener, 'Info count false.');

			// Class name should be the same.
			$sMsg = 'get_class info false, item ';
			$iMax = count($aListener['info']);
			for ($iFor = 0; $iFor < $iMax; $iFor++)
			{
				$sExpected  = $aExpected['info'][$iFor];
				$sClassName = get_class($aListener['info'][$iFor]);
				$this->assertEquals($sExpected, $sClassName, $sMsg . $iFor);
			} // for
		} // if

		if ((isset($aExpected['object']) === true) &&
			(empty($aExpected['object']) === false))
		{
			$this->assertTrue(isset($aListener['object']), 'No object set.');

			$iListener = count($aListener['object']);
			$this->assertEquals(count($aExpected['object']), $iListener, 'Listener count false.');

			// Class name should be the same.
			$sMsg = 'get_class object false, item ';
			$iMax = count($aListener['object']);
			for ($iFor = 0; $iFor < $iMax; $iFor++)
			{
				$sExpected  = $aExpected['object'][$iFor];
				$sClassName = get_class($aListener['object'][$iFor]);
				$this->assertEquals($sExpected, $sClassName, $sMsg . $iFor);
			} // for
		} // if
	} // function
} // class
