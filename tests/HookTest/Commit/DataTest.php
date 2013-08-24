<?php
/**
 * Commit Data Tests, dedicated for zabu.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Commit;

use Hook\Commit\Data;
use Hook\Commit\Info;
use Hook\Commit\Object;
use Hook\Filter\ObjectFilter;
use Hook\Listener\AbstractObject;
use HookTest\Commit\DataTestHelper;

require_once __DIR__ . '/../../Bootstrap.php';
require_once 'DataTestHelper.php';

/**
 * Commit Data Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class DataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Commit Data Object.
     * @var Data
     */
    private $oData;

    /**
     * Set Up Method.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUp()
    {
        $this->oData = new Data(array('A', 'U', 'D'));

        // Default values.
        $aMock = array(
            'functions' => array('getAction', 'getObjectPath', 'isDir'),
            'values'    => array(
                0 => array(
                    'txn'    => '74-1',
                    'rev'    => 74,
                    'action' => 'A',
                    'item'   => '/path/zabu-1.php',
                    'real'   => '/path/zabu-1.php',
                    'ext'    => 'php',
                    'isdir'  => false,
                    'info'   => null,
                    'props'  => array(),
                    'lines'  => array()
                )
            )
        );

        // A file object using default.
        $this->oData->addObject($this->setUpObject($aMock));

        // A file object.
        $aValues = array(
            'action' => 'U',
            'item'   => '/path/zabu-2.php',
            'real'   => '/path/zabu-2.php',
            'ext'    => 'php',
            'isdir'  => false
        );

        $aMock['values'][0] = array_merge($aMock['values'][0], $aValues);
        $this->oData->addObject($this->setUpObject($aMock));

        // A file object.
        $aValues            = array(
            'action' => 'D',
            'item'   => '/path/zabu-3.php',
            'real'   => '/path/zabu-3.php',
            'ext'    => 'php',
            'isdir'  => false
        );
        $aMock['values'][0] = array_merge($aMock['values'][0], $aValues);
        $this->oData->addObject($this->setUpObject($aMock));

        // A directory object.
        $aValues            = array(
            'action' => 'U',
            'item'   => '/path/to/zabu',
            'real'   => '/path/to/zabu',
            'ext'    => '',
            'isdir'  => true
        );
        $aMock['values'][0] = array_merge($aMock['values'][0], $aValues);
        $this->oData->addObject($this->setUpObject($aMock));

        // A file object.
        $aValues            = array(
            'action' => 'A',
            'item'   => '/path/zabu-1.html',
            'real'   => '/path/zabu-1.html',
            'ext'    => 'html',
            'isdir'  => false
        );
        $aMock['values'][0] = array_merge($aMock['values'][0], $aValues);
        $this->oData->addObject($this->setUpObject($aMock));

        // A file object.
        $aValues            = array(
            'action' => 'U',
            'item'   => '/path/zabu-2.html',
            'real'   => '/path/zabu-2.html',
            'ext'    => 'html',
            'isdir'  => false
        );
        $aMock['values'][0] = array_merge($aMock['values'][0], $aValues);
        $this->oData->addObject($this->setUpObject($aMock));

        // A file object.
        $aValues            = array(
            'action' => 'D',
            'item'   => '/path/zabu-3.html',
            'real'   => '/path/zabu-3.html',
            'ext'    => 'html',
            'isdir'  => false
        );
        $aMock['values'][0] = array_merge($aMock['values'][0], $aValues);
        $this->oData->addObject($this->setUpObject($aMock));
    }

    /**
     * Set up test info object.
     * @return Info
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUpInfoObject()
    {
        $aInfo             = array();
        $aInfo['txn']      = '74-1';
        $aInfo['rev']      = 74;
        $aInfo['user']     = 'Zabu';
        $aInfo['datetime'] = '2008-12-30 12:23:45';
        $aInfo['message']  = '* A message for this tests.';

        // Create a stub for the SomeClass class.
        $oStub = $this->getMock('Hook\Commit\Info', array('getUser'), $aInfo);

        // Configure the stub.
        $oStub->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue('Zabu'));

        return $oStub;
    }

    /**
     * Set up test object item.
     * @param array $aParams Parameter for the mock object.
     * @return Object
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUpObject(array $aParams)
    {
        // Create a stub for the SomeClass class.
        $oStub = $this->getMock('Hook\Commit\Object', $aParams['functions'], $aParams['values']);

        // Configure the stub.
        $oStub->expects($this->any())
            ->method('isDir')
            ->will($this->returnValue($aParams['values'][0]['isdir']));

        $oStub->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue($aParams['values'][0]['action']));

        $oStub->expects($this->any())
            ->method('getObjectPath')
            ->will($this->returnValue($aParams['values'][0]['item']));

        $oStub->expects($this->any())
            ->method('getFileExtension')
            ->will($this->returnValue($aParams['values'][0]['ext']));

        return $oStub;
    }

    /**
     * Setup a mock listener.
     * @param array $aRegister Register data.
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUpListener(array $aRegister)
    {
        $oStub = new DataTestHelper;
        $oStub->setRegister($aRegister);

        // Create a stub for the SomeClass class.
        /*
        $aFunctions = array(
            'register',
            'getObjectFilter'
        );

        $oStub = $this->getMock(
            'HookTest\Commit\DataTestHelper',
            $aFunctions,
            array(),
            'Hook\Listener\AbstractObject'
        );

        // Configure the stub.
        $oStub->expects($this->once())
            ->method('register')
            ->will($this->returnValue($aRegister));

        $oStub->expects($this->any())
            ->method('getObjectFilter')
            ->will($this->returnValue(new ObjectFilter()));
        */
        return $oStub;
    }

    /**
     * Test set and get info object.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testSetInfoObject()
    {
        $oInfo = $this->setUpInfoObject();

        $this->oData->setInfo($oInfo);
        $this->assertSame($oInfo, $this->oData->getInfo());
    }

    /**
     * Test with empty file action. Will cause all fileactions default.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testEmptyRegister()
    {
        $oListener = $this->setUpListener(array());
        $aFiles    = $this->oData->getObjects($oListener);

        $this->assertCount(0, $aFiles, '$aFiles count wrong');
    }

    /**
     * Test with empty file action. Will cause all fileactions default.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testEmptyActionAndExtension()
    {
        // Test register data.
        $aRegister = array(
            'action'     => 'commit',
            'fileaction' => array(),
            'extensions' => array(),
            'withdirs'   => false
        );

        $oListener = $this->setUpListener($aRegister);
        $aFiles    = $this->oData->getObjects($oListener);

        $this->assertCount(0, $aFiles, '$aFiles count wrong');
    }

    /**
     * Test with empty file action. Will cause all fileactions default.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testEmptyFileAction()
    {
        // Test register data.
        $aRegister = array(
            'action'     => 'commit',
            'fileaction' => array(),
            'extensions' => array('ALL'),
            'withdirs'   => false
        );

        $oListener = $this->setUpListener($aRegister);
        $aFiles    = $this->oData->getObjects($oListener);

        $this->assertCount(6, $aFiles, '$aFiles count wrong');
        $this->assertEquals('/path/zabu-3.php', $aFiles[4]->getObjectPath(), 'objectpath wrong');
    }

    /**
     * Test with empty extensions. Will cause all extensions default.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testEmptyExtension()
    {
        // Test register data.
        $aRegister = array(
            'action'     => 'commit',
            'fileaction' => array(
                'A'
            ),
            'extensions' => array(),
            'withdirs'   => false
        );

        $oListener = $this->setUpListener($aRegister);
        $aFiles    = $this->oData->getObjects($oListener);

        $this->assertCount(2, $aFiles, '$aFiles count wrong');
        $this->assertEquals('/path/zabu-1.html', $aFiles[1]->getObjectPath(), 'objectpath wrong');
        $this->assertEquals('A', $aFiles[0]->getAction(), 'action #1 wrong');
        $this->assertEquals('A', $aFiles[1]->getAction(), 'action #2 wrong');
    }

    /**
     * Test to give the correct files for the listener.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetObjectsForListener()
    {
        // Test register data.
        $aRegister = array(
            'action'     => 'commit',
            'fileaction' => array(
                'A', 'U', 'D'
            ),
            'extensions' => array('ALL'),
            'withdirs'   => false
        );

        $oListener = $this->setUpListener($aRegister);
        $aFiles    = $this->oData->getObjects($oListener);

        $this->assertCount(6, $aFiles, '$aFiles count wrong');
        $this->assertEquals('/path/zabu-1.html', $aFiles[1]->getObjectPath(), 'objectpath wrong');
    }

    /**
     * Test directories and files after action and extension.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetFilesWithDirs()
    {
        // Test register data.
        $aRegister = array(
            'action'     => 'commit',
            'fileaction' => array(
                'A', 'U', 'D'
            ),
            'extensions' => array('ALL'),
            'withdirs'   => true
        );

        $oListener = $this->setUpListener($aRegister);
        $aFiles    = $this->oData->getObjects($oListener);

        $this->assertCount(7, $aFiles, 'count $aFiles wrong');
        $this->assertEquals('/path/zabu-2.php', $aFiles[2]->getObjectPath(), 'objectpath wrong');
    }

    /**
     * Test return all actions, when Array in register method is empty.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetFilesActionTypes()
    {
        // Test register data.
        $aRegister = array(
            'action'     => 'commit',
            'fileaction' => array(
                'A', 'U'
            ),
            'extensions' => array('ALL'),
            'withdirs'   => false
        );

        $oListener = $this->setUpListener($aRegister);
        $aFiles    = $this->oData->getObjects($oListener);

        $this->assertCount(4, $aFiles, 'count $aFiles wrong');
        $this->assertEquals('/path/zabu-1.html', $aFiles[1]->getObjectPath(), 'objectpath wrong');
        $this->assertEquals('A', $aFiles[0]->getAction(), 'action #0 wrong');
        $this->assertEquals('A', $aFiles[1]->getAction(), 'action #1 wrong');
        $this->assertEquals('U', $aFiles[2]->getAction(), 'action #2 wrong');
        $this->assertEquals('U', $aFiles[3]->getAction(), 'action #3 wrong');
    }

    /**
     * Test return all extensions, when register array is empty.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetFilesExtensions()
    {
        // Test register data.
        $aRegister = array(
            'action'     => 'commit',
            'fileaction' => array(
                'A', 'U', 'D'
            ),
            'extensions' => array('html'),
            'withdirs'   => false
        );

        $oListener = $this->setUpListener($aRegister);
        $aFiles    = $this->oData->getObjects($oListener);

        $this->assertCount(3, $aFiles, 'file count wrong');
        $this->assertEquals('/path/zabu-1.html', $aFiles[0]->getObjectPath(), 'objectpath wrong');
    }

    /**
     * Test that no action, extension or directory will match
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testNothingMatches()
    {
        // Test register data.
        $aRegister = array(
            'action'     => 'commit',
            'fileaction' => array(
                'D'
            ),
            'extensions' => array('sh'),
            'withdirs'   => false
        );

        $oListener = $this->setUpListener($aRegister);
        $aFiles    = $this->oData->getObjects($oListener);

        $this->assertCount(0, $aFiles);
    }
}