<?php
/**
 * Configuration objects tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.1.0
 */

namespace HookTest\Core;

use Hook\Core\Config;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Configuration objects tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 2.1.0
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Config object.
     * @var Config
     */
    protected $oConfig;

    /**
     * Setup
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUp()
    {
        $this->oConfig = new Config;
    }

    /**
     * Test that the given file does not extists
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testFileNotExists()
    {
        $this->assertFalse($this->oConfig->loadConfigFile(__DIR__ . '/_files/not-exists.ini'));
    }

    /**
     * Test load configuration file.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testLoadConfiguration()
    {
        $this->oConfig->loadConfigFile(__DIR__ . '/_files/test-config.ini');

        $aExp = array(
            'Filter'   => array(
                'Directory'            => array('Filter/Filtered/', 'Filter/Filtered2/'),
                'Files'                => array('Filter/NotFiltered/FilteredFile.php'),
                'WhiteListDirectories' => array('Filter/Filtered/WhiteList/'),
                'WhiteListFiles'       => array('Filter/Filtered/WhiteFile.php')
            ),
            'Standard' => 'PEAR',
            'Style'    => array('TabWidth' => '4')
        );

        $this->assertSame($aExp, $this->oConfig->getConfiguration('Pre', 'Style'));
    }

    /**
     * Test load not available configuration.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetNoneExistingConfiguration()
    {
        $this->oConfig->loadConfigFile(__DIR__ . '/_files/config.ini');

        $this->assertFalse($this->oConfig->getConfiguration('Not', 'Exists'));
    }

    /**
     * Test load not available listener configuration.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetNoneExistingListenerConfiguration()
    {
        $this->oConfig->loadConfigFile(__DIR__ . '/_files/config.ini');

        $this->assertFalse($this->oConfig->getConfiguration('Pre', 'NotExists'));
    }

    /**
     * Test get standard configuration.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetStandardConfiguration()
    {
        $this->oConfig->loadConfigFile(__DIR__ . '/_files/test-config.ini');

        $sExpected = '/path/to/hookframework/tests/Core/svn/';
        $this->assertSame($sExpected, $this->oConfig->getConfiguration('vcs', 'binary_path'));
    }
}
