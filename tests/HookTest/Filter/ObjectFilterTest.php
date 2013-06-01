<?php
/**
 * Filter Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Filter
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Filter;

use Hook\Filter\ObjectFilter;

require_once __DIR__ . '/../../Bootstrap.php';

/**
 * Filter Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Filter
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class ObjectFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test object.
     * @var ObjectFilter
     */
    private $oObjectFilter;

    /**
     * SetUp operation.
     * @return void
     * @author Alexander Zimmermann <alex@zimmemann.com>
     */
    protected function setUp()
    {
        $this->oObjectFilter = new ObjectFilter();
    }

    /**
     * Data provider for AddDirectoryToFilter test.
     * @return array
     * @author Alexander Zimmermann <alex@zimmemann.com>
     */
    public static function getDirectories()
    {
        return array(
            array(
                array(
                    '/path/to/a/directory',
                    '/another/path/to/a/directory',
                    '/another/path/to/a/directory',
                    '/the/same/above/should/not/be/added'
                ),
                3
            ),
            array(
                array(
                    '/path/to/a/directory',
                    '/another/path/to/a/directory',
                    '/another/path/to/a/directory/',
                    '/the/same/above/should/not/be/added/'
                ),
                3
            ),
            array(
                array(
                    '/path/to/a/directory',
                    '/the/same/above/should/not/be/added'
                ),
                2
            )
        );
    }

    /**
     * Test for the directory filter function.
     * @param array   $aData     Test data set.
     * @param integer $iExpected Expected entries count.
     * @return void
     * @author       Alexander Zimmermann <alex@zimmemann.com>
     * @dataProvider getDirectories
     */
    public function testAddDirectoryToFilter(array $aData, $iExpected)
    {
        $iMax = count($aData);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $this->oObjectFilter->addDirectoryToFilter($aData[$iFor]);
        } // for

        $iActual = count($this->oObjectFilter->getFilteredDirectories());
        $this->assertEquals($iExpected, $iActual);
    }

    /**
     * Data provider for AddFileToFilter test.
     * @return array
     * @author Alexander Zimmermann <alex@zimmemann.com>
     */
    public static function getFiles()
    {
        return array(
            array(
                array(
                    '/path/to/a/file/to/be/filtered.php',
                    '/another/path/to/a/file/to/be/filtered.php',
                    '/path/to/a/file/to/be/filtered.php',
                    '/the/same/above/should/not/be/added/above.php',
                    '/this/should/not/be/added/no/file/'
                ),
                3
            ),
            array(
                array(
                    '/path/to/a/file/to/be/filtered.php',
                    '/another/path/to/a/file/to/be/filtered.php'
                ),
                2
            )
        );
    }

    /**
     * Test for the file filter function.
     * @param array   $aData     Test data set.
     * @param integer $iExpected Expected entries count.
     * @return void
     * @author       Alexander Zimmermann <alex@azimmermann.com>
     * @dataProvider getFiles
     */
    public function testAddFileToFilter(array $aData, $iExpected)
    {
        $iMax = count($aData);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $this->oObjectFilter->addFileToFilter($aData[$iFor]);
        } // for

        $iActual = count($this->oObjectFilter->getFilteredFiles());
        $this->assertEquals($iExpected, $iActual);
    }

    /**
     * Data provider for addDirectoryToWhiteList test.
     * @return array
     * @author Alexander Zimmermann <alex@zimmemann.com>
     */
    public static function getWhiteListDirectories()
    {
        return array(
            array(
                array(
                    '/path/to/a/directory',
                    '/another/path/to/a/directory',
                    '/another/path/to/a/directory',
                    '/the/same/above/should/not/be/added'
                ),
                3
            ),
            array(
                array(
                    '/path/to/a/directory',
                    '/another/path/to/a/directory',
                    '/another/path/to/a/directory/',
                    '/the/same/above/should/not/be/added/'
                ),
                3
            ),
            array(
                array(
                    '/path/to/a/directory',
                    '/the/same/above/should/not/be/added'
                ),
                2
            )
        );
    }

    /**
     * Test for the directory white list function.
     * @param array   $aData     Test data set.
     * @param integer $iExpected Expected entries count.
     * @return void
     * @author       Alexander Zimmermann <alex@azimmermann.com>
     * @dataProvider getWhiteListDirectories
     */
    public function testAddDirectoryToWhiteList(array $aData, $iExpected)
    {
        $iMax = count($aData);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $this->oObjectFilter->addDirectoryToWhiteList($aData[$iFor]);
        } // for

        $iActual = count($this->oObjectFilter->getWhiteListDirectories());
        $this->assertEquals($iExpected, $iActual);
    }

    /**
     * Data provider for AddFileToFilter test.
     * @return array
     * @author Alexander Zimmermann <alex@zimmemann.com>
     */
    public static function getWhiteListFiles()
    {
        return array(
            array(
                array(
                    '/file/in/a/path/that/is/filtered/should/be/allowed.php',
                    '/file/in/a/path/that/is/filtered/should/be/allowed.php'
                ),
                1
            ),
            array(
                array(
                    '/file/in/a/path/that/is/filtered/should/be/allowed.php',
                    '/file/in/a/path/that/is/filtered/should/be/allowed2.php',
                    '/file/in/a/path/that/is/filtered/should/be/allowed3.php'
                ),
                3
            ),
            array(
                array(
                    '/file/in/a/path/that/is/filtered/should/be/allowed.php',
                    '/file/in/a/path/that/is/filtered/should/be/allowed2.php',
                    '/this/cause/an/error/cause/of/directory/'
                ),
                2
            ),
            array(
                array(
                    '/file/in/a/path/that/is/filtered/should/be/allowed.php',
                    '/file/in/a/path/that/is/filtered/should/be/allowed2.php',
                    '/this/is/allowed/cause/its/a/file/wihtout/extension'
                ),
                3
            )
        );
    }

    /**
     * Test for the file white list function.
     * @param array   $aData     Test data set.
     * @param integer $iExpected Expected entries count.
     * @return void
     * @author       Alexander Zimmermann <alex@azimmermann.com>
     * @dataProvider getWhiteListFiles
     */
    public function testAddFileToWhiteList(array $aData, $iExpected)
    {
        $iMax = count($aData);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $this->oObjectFilter->addFileToWhiteList($aData[$iFor]);
        } // for

        $iActual = count($this->oObjectFilter->getWhiteListFiles());
        $this->assertEquals($iExpected, $iActual);
    }
}
