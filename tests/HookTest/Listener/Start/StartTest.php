<?php
/**
 * Start Test.
 * @category   Tests
 * @package    Listener
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@zimmemann.com>
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Listener\Start;

use Example\Start\Start;

require_once __DIR__ . '/../../../Bootstrap.php';

require_once __DIR__ . '/../../../../Repositories/Example/Start/Start.php';

/**
 * Start Test.
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
class StartTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test object for Start.
	 * @var Start
	 */
	private $oStartListener;

	/**
	 * SetUp operations.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oStartListener = new Start();
	} // function

	/**
	 * Test that the object was created.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testObject()
	{
		$sClass = get_class($this->oStartListener);
		$this->assertEquals('Example\Start\Start', $sClass);
	} // function
} // class
