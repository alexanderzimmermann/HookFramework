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

require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Core/Listener/ListenerInfoAbstract.php';
require_once 'Core/Commit/CommitInfo.php';
require_once 'Listener/Start/Start.php';

/**
 * Start Test.
 * @category   Tests
 * @package    Listener
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class StartTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Testobjekt fuer Start.
	 * @var Start
	 */
	private $oStartListener;

	/**
	 * SetUp Operationen.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	protected function setUp()
	{
		$this->oStartListener = new Start();
	} // function

	/**
	 * Testen ob das Objekt erzeugt wurde.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testObject()
	{
		$sClass = get_class($this->oStartListener);
		$this->assertEquals('Start', $sClass);
	} // function
} // class
