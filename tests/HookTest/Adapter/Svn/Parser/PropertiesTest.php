<?php
/**
 * Test class for parsing the diff of a property change set.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Core\Commit\Parser;

use Hook\Adapter\Svn\Parser\Properties;

require_once __DIR__ . '/../../../../Bootstrap.php';

/**
 * Test class for parsing the diff of a property change set.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class DiffPropertiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test class to be tested.
     * @var Properties
     */
    protected $oFixture;

    /**
     * Sets up the fixture.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setUp()
    {
        $this->oFixture = new Properties;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function tearDown()
    {
    }

    /**
     * Test parsing.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParseOnlyProperties()
    {
        $sFile  = HF_TEST_FILES_DIR . 'txn/1749-1/diff.txt';
        $aLines = file($sFile, FILE_IGNORE_NEW_LINES);

        // Remove first 2 lines and the last one like parser should do.
        array_shift($aLines);
        array_shift($aLines);
        array_pop($aLines);

        $this->oFixture->parse(1, $aLines);

        $aProperties = $this->oFixture->getProperties();

        $this->assertTrue(isset($aProperties[1]), 'Properties not in id 1');
        $this->assertSame(1, count($aProperties[1]), 'Less or more items for id 1');
        $this->assertTrue(isset($aProperties[1]['svn:ignore']), 'wrong index, not svn:ignore');

        $oProperty = $aProperties[1]['svn:ignore'];

        $sValue = "config.ini\nexceptions.inc.php\nmodules.inc.php\n\n";
        $this->assertSame($sValue, $oProperty->getOldValue(), 'Old value wrong.');

        $sValue = "config.ini\nexceptions.inc.php\nmodules.inc.php\ncontrollers.inc.php";
        $this->assertSame($sValue, $oProperty->getNewValue(), 'New value wrong.');
    }

    /**
     * Test more than one property change.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParseManyPropertyChanges()
    {
        $aLines   = array();
        $aLines[] = 'Property changes on: repos/trunk/tmp/folder/file3.php';
        $aLines[] = '___________________________________________________________________';
        $aLines[] = 'Name: svn:executable';
        $aLines[] = '   - *';
        $aLines[] = 'Name: text';
        $aLines[] = '   - Your value';
        $aLines[] = 'with 2 lines.';
        $aLines[] = '   + My value';
        $aLines[] = 'with 2 lines.';
        $aLines[] = 'Name: svn:keywords';
        $aLines[] = '   + Id';

        $this->oFixture->parse(1, $aLines);

        $aProperties = $this->oFixture->getProperties();

        $this->assertTrue(isset($aProperties[1]), 'Properties not in id 1');

        $aKeys = array(
            'svn:executable',
            'text',
            'svn:keywords'
        );

        $this->assertSame($aKeys, array_keys($aProperties[1]), 'Wrong indexes in properties.');

        $oProperty = $aProperties[1]['svn:executable'];

        $this->assertSame("*\n", $oProperty->getOldValue(), 'Old value wrong.');
        $this->assertNull($oProperty->getNewValue(), 'New value wrong.');

        $oProperty = $aProperties[1]['text'];
        $this->assertSame("My value\nwith 2 lines.", $oProperty->getNewValue(), 'Wrong new value');
    }

    /**
     * Test more than one property change.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParsePropertyAddedSvnVersion166()
    {
        $aLines[] = 'Property changes on: trunk/tmp/newfolder1/newfolder1_1/correct_file1.php';
        $aLines[] = '___________________________________________________________________';
        $aLines[] = 'Added: svn:keywords';
        $aLines[] = '   + Id';
        $aLines[] = '';

        $this->oFixture->parse(1, $aLines);

        $aProperties = $this->oFixture->getProperties();

        $this->assertTrue(isset($aProperties[1]), 'Properties not in id 1');

        $aKeys = array('svn:keywords');

        $this->assertSame($aKeys, array_keys($aProperties[1]), 'Wrong indexes in properties.');

        $oProperty = $aProperties[1]['svn:keywords'];

        $this->assertNull($oProperty->getOldValue(), 'Old value not null.');
        $this->assertSame('Id', $oProperty->getNewValue(), 'New value wrong.');
    }

    /**
     * Test more than one property change.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParsePropertyModifiedSvnVersion166()
    {
        $aLines[] = 'Property changes on: trunk/tmp/newfolder1/newfolder1_1/correct_file1.php';
        $aLines[] = '___________________________________________________________________';
        $aLines[] = 'Modified: svn:keywords';
        $aLines[] = '   - Id';
        $aLines[] = '   + Id Author Date Revision';
        $aLines[] = '';

        $this->oFixture->parse(1, $aLines);

        $aProperties = $this->oFixture->getProperties();

        $this->assertTrue(isset($aProperties[1]), 'Properties not in id 1');

        $aKeys = array('svn:keywords');

        $this->assertSame($aKeys, array_keys($aProperties[1]), 'Wrong indexes in properties.');

        $oProperty = $aProperties[1]['svn:keywords'];

        $this->assertSame("Id\n", $oProperty->getOldValue(), 'Old value wrong.');
        $this->assertSame('Id Author Date Revision', $oProperty->getNewValue(), 'New value wrong.');
    }

    /**
     * Test more than one property change.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParsePropertyDeletedSvnVersion166()
    {
        $aLines[] = 'Property changes on: trunk/tmp/newfolder1/newfolder1_1/correct_file1.php';
        $aLines[] = '___________________________________________________________________';
        $aLines[] = 'Deleted: svn:keywords';
        $aLines[] = '   - Id';
        $aLines[] = '';

        $this->oFixture->parse(1, $aLines);

        $aProperties = $this->oFixture->getProperties();

        $this->assertTrue(isset($aProperties[1]), 'Properties not in id 1');

        $aKeys = array('svn:keywords');

        $this->assertSame($aKeys, array_keys($aProperties[1]), 'Wrong indexes in properties.');

        $oProperty = $aProperties[1]['svn:keywords'];

        $this->assertSame("Id\n\n", $oProperty->getOldValue(), 'Old value wrong.');
        $this->assertNull($oProperty->getNewValue(), 'New value is not null.');
    }

    /**
     * Test more than one property change.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testParsePropertyMixedSvnVersion166()
    {
        $aLines[] = 'Property changes on: trunk/tmp/newfolder1/newfolder1_1/correct_file1.php';
        $aLines[] = '___________________________________________________________________';
        $aLines[] = 'Modified: svn:keywords';
        $aLines[] = '   - Id';
        $aLines[] = '   + Id Author';
        $aLines[] = 'Added: svn:executable';
        $aLines[] = '   + *';
        $aLines[] = '';

        $this->oFixture->parse(1, $aLines);

        $aProperties = $this->oFixture->getProperties();

        $this->assertTrue(isset($aProperties[1]), 'Properties not in id 1');

        $aKeys = array(
            'svn:keywords',
            'svn:executable'
        );

        $this->assertSame($aKeys, array_keys($aProperties[1]), 'Wrong indexes in properties.');

        $oProperty = $aProperties[1]['svn:keywords'];

        $this->assertSame("Id\n", $oProperty->getOldValue(), 'Old value wrong.');
        $this->assertSame('Id Author', $oProperty->getNewValue(), 'New value wrong.');
    }

    /**
     * Test on empty lines.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testEmptyProperties()
    {
        $this->oFixture->parse(1, array());
        $this->assertSame(array(), $this->oFixture->getProperties());
    }
}