<?php
/**
 * Configuration objects tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
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
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
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
    } // function

    /**
     * Test that the given file does not extists
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testFileNotExists()
    {
        $this->assertFalse($this->oConfig->loadConfigFile(__DIR__ . '/_files/not-exists.ini'));
    } // function

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
                'WhitelistDirectories' => array('Filter/Filtered/Whitelist/'),
                'WhitelistFiles'       => array('Filter/Filtered/WhiteFile.php')
            ),
            'Standard' => 'PEAR',
            'Style'    => array('TabWidth' => '4')
        );

        $this->assertSame($aExp, $this->oConfig->getConfiguration('Pre', 'Style'));
    } // function

    /**
     * Test load not available configuration.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetNoneExistingConfiguration()
    {
        $this->oConfig->loadConfigFile(__DIR__ . '/_files/config.ini');

        $this->assertFalse($this->oConfig->getConfiguration('Not', 'Exists'));
    } // function

    /**
     * Test load not available listener configuration.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testGetNoneExistingListenerConfiguration()
    {
        $this->oConfig->loadConfigFile(__DIR__ . '/_files/config.ini');

        $this->assertFalse($this->oConfig->getConfiguration('Pre', 'NotExists'));
    } // function
} // class
