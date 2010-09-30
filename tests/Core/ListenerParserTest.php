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

require_once dirname(__FILE__) . '/../TestHelper.php';

require_once 'Core/Arguments.php';
require_once 'Core/Listener/ListenerParser.php';

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
class ListenerParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Fake SVN Pfad.
	 * @var string
	 */
	static private $sSvn;

	/**
	 * Testen auf Fehler in Listener.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testListenerParserFailures()
	{
		$aFunctions = array(
					   'getMainType', 'getSubActions'
					  );
		$aArguments = array(
					   array(
						0 => '/var/local/svn/hooks/Hook',
						1 => dirname(__FILE__) . '/svn',
						2 => 'testuser12',
						3 => 'pre-commit'
					   )
					  );
		$sReturnVal = '../tests/Listener/Failures';
		$oArguments = $this->getMock('Arguments', $aFunctions, $aArguments);
		$oArguments->expects($this->any())
				   ->method('getMainType')
				   ->will($this->returnValue($sReturnVal));

		$aReturnVal = array('commit');
		$oArguments->expects($this->any())
				   ->method('getSubActions')
				   ->will($this->returnValue($aReturnVal));

		$oListenerParser = new ListenerParser($oArguments);
		$aListener       = $oListenerParser->getListener();

		$this->assertTrue(is_array($aListener), '$aListener is not an array.');
		$this->assertTrue(isset($aListener['info']), 'Info Element is not set in $aListener.');
		$this->assertTrue(isset($aListener['object']), 'Object Element is not set in $aListener.');

		// Info Listener Test.
		$this->assertEquals(1, count($aListener['info']), 'Info Listener count not 1.');
		$sExpectedName = 'Test Info Listener Ok.';
		$sActualName   = $aListener['info'][0]->getListenerName();
		$this->assertEquals($sExpectedName, $sActualName, 'Info Listener Name not correct!');

		// Object Listener Test.
		$this->assertEquals(1, count($aListener['object']), 'Info Listener count not 1.');
		$sExpectedName = 'Test Object Listener Ok.';
		$sActualName   = $aListener['object'][0]->getListenerName();
		$this->assertEquals($sExpectedName, $sActualName, 'Object Listener Name not correct!');
	} // function

	/**
	 * Dataprovider.
	 * @return array
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public static function getArguments()
	{
		self::$sSvn = dirname(__FILE__) . '/svn';

		return array(
				array(
				 array(
				  0        => '/var/local/svn/hooks/Hook',
				  1        => self::$sSvn,
				  2        => '666-1',
				  3        => 'pre-commit',
				  'info'   => array(
							   'Message', 'MessageStrict'
							  ),
				  'object' => array(
							   'Id', 'Style', 'Syntax'
							  )
				 )
				),
				array(
				 array(
				  0        => '/var/local/svn/hooks/Hook',
				  1        => self::$sSvn,
				  2        => 666,
				  3        => 'post-commit',
				  'info'   => array('Mailing'),
				  'object' => array('Diff')
				 )
				),
				array(
				 array(
				  0        => '/var/local/svn/hooks/Hook',
				  1        => self::$sSvn,
				  2        => 'testuser12',
				  3        => 'start-commit',
				  'info'   => array('Start'),
				  'object' => array()
				 )
				)
			   );
	} // function

	/**
	 * Test the listener parser.
	 *
	 * Simple test with the available listener of the framework.
	 * @param array $aData Testdata.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @dataProvider getArguments
	 */
	public function testListenerParserObjects(array $aData)
	{
		// Entfernen fuer die Parameter.
		$aParams = $aData;
		unset($aParams['info']);
		unset($aParams['object']);

		$oArguments = new Arguments($aParams);
		$this->assertTrue($oArguments->argumentsOk(), 'Arguments not ok.');

		$oListenerParser = new ListenerParser($oArguments);
		$aListener       = $oListenerParser->getListener();

		$this->assertFalse(empty($aListener), 'Listener empty.');

		if (empty($aListener) === false)
		{
			if ((isset($aData['info']) === true) &&
				(empty($aData['info']) === false))
			{
				$this->assertTrue(isset($aListener['info']), 'No info set.');

				$iInfoListener = count($aListener['info']);
				$this->assertEquals($iInfoListener, count($aData['info']), 'Info count false.');

				// Der Klassenname sollte identisch sein.
				$sMsg = 'get_class info false, item ';
				$iMax = count($aListener['info']);
				for ($iFor = 0; $iFor < $iMax; $iFor++)
				{
					$sClassName = get_class($aListener['info'][$iFor]);
					$this->assertEquals($aData['info'][$iFor], $sClassName, $sMsg . $iFor);
				} // for
			} // if

			if ((isset($aData['object']) === true) &&
				(empty($aData['object']) === false))
			{
				$this->assertTrue(isset($aListener['object']), 'No object set.');

				$iListener = count($aListener['object']);
				$this->assertEquals(count($aData['object']), $iListener, 'Listener count false.');

				// Der Klassenname sollte identisch sein.
				$sMsg = 'get_class object false, item ';
				$iMax = count($aListener['object']);
				for ($iFor = 0; $iFor < $iMax; $iFor++)
				{
					$sClassName = get_class($aListener['object'][$iFor]);
					$this->assertEquals($aData['object'][$iFor], $sClassName, $sMsg . $iFor);
				} // for
			} // if
		} // if
	} // function
} // class
