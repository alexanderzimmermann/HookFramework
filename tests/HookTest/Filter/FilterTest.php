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

use Hook\Commit\Info;
use Hook\Commit\Object;
use Hook\Filter\Filter;
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
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test add Directory to filter.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testFilter()
    {
        // Create commit info object.
        $sUser = 'Juliana';
        $sDate = '2008-12-30 09:13:38';
        $sMsg  = '+ a comment';

        $oInfo = new Info('74-1', 0, $sUser, $sDate, $sMsg);

        // Create commit file objects.
        $sBase  = 'hookfraemwork/trunk/tmp/filter/filter_directory/';
        $sReal  = 'tmp/filter/filter_directory/';
        $sBase2 = 'hookfraemwork/trunk/tmp/newfolder1/newfolder1_1/';
        $sReal2 = 'tmp/newfolder1/newfolder1_1/';

        $aFiles[0]['path'] = $sBase;
        $aFiles[0]['real'] = $sReal;
        $aFiles[0]['ext']  = '';
        $aFiles[0]['dir']  = true;
        $aFiles[0]['xn']   = 'A';
        $aFiles[1]['path'] = $sBase . 'filter_file1.php';
        $aFiles[1]['real'] = $sReal . 'filter_file1.php';
        $aFiles[1]['ext']  = 'php';
        $aFiles[1]['dir']  = false;
        $aFiles[1]['xn']   = 'A';
        $aFiles[2]['path'] = $sBase . 'filter_file2.php';
        $aFiles[2]['real'] = $sReal . 'filter_file2.php';
        $aFiles[2]['ext']  = 'php';
        $aFiles[2]['dir']  = false;
        $aFiles[2]['xn']   = 'A';
        $aFiles[3]['path'] = $sBase2 . 'correct_file1.php';
        $aFiles[3]['real'] = $sReal2 . 'correct_file1.php';
        $aFiles[3]['ext']  = 'php';
        $aFiles[3]['dir']  = false;
        $aFiles[3]['xn']   = 'U';
        $aFiles[4]['path'] = $sBase2 . 'correct_file2.php';
        $aFiles[4]['real'] = $sReal2 . 'correct_file2.php';
        $aFiles[4]['ext']  = 'php';
        $aFiles[4]['dir']  = false;
        $aFiles[4]['xn']   = 'U';
        $aFiles[5]['path'] = $sBase2 . 'parse-error_file1.php';
        $aFiles[5]['real'] = $sReal2 . 'parse-error_file1.php';
        $aFiles[5]['ext']  = 'php';
        $aFiles[5]['dir']  = false;
        $aFiles[5]['xn']   = 'U';
        $aFiles[6]['path'] = $sBase . 'filter_file_whitelist.php';
        $aFiles[6]['real'] = $sReal . 'filter_file_whitelist.php';
        $aFiles[6]['ext']  = 'php';
        $aFiles[6]['dir']  = false;
        $aFiles[6]['xn']   = 'A';

        $iMax     = count($aFiles);
        $aObjects = array();
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $aParams = array(
                'txn'    => '74-1',
                'rev'    => 0,
                'action' => $aFiles[$iFor]['xn'],
                'item'   => $aFiles[$iFor]['path'],
                'real'   => $aFiles[$iFor]['real'],
                'ext'    => $aFiles[$iFor]['ext'],
                'isdir'  => $aFiles[$iFor]['dir'],
                'props'  => array(),
                'lines'  => null,
                'info'   => $oInfo
            );

            $aObjects[] = new Object($aParams);
        }

        // Create filter object.
        $oObjectFilter = new ObjectFilter();

        $sFilterDirectory = 'tmp/filter/filter_directory/';
        $sWhiteListFile   = $sFilterDirectory . 'filter_file_whitelist.php';

        // Filter for the style.
        $oObjectFilter->addDirectoryToFilter($sFilterDirectory);
        $oObjectFilter->addFileToWhiteList($sWhiteListFile);

        // Ignore test files.
        $sParseErrorFile = $sReal2 . 'parse-error_file1.php';
        $oObjectFilter->addFileToFilter($sParseErrorFile);

        // Yes, this file doesn't exists.
        $sParseErrorFile = $sReal2 . 'parse-error_file2.php';
        $oObjectFilter->addFileToFilter($sParseErrorFile);

        // Create filter.
        $oFilter = new Filter($aObjects);
        $aActual = $oFilter->getFilteredFiles($oObjectFilter);

        // Tests.
        $this->assertEquals(3, count($aActual), 'Count false.');
        $this->assertEquals($aFiles[6]['path'], $aActual[0]->getObjectPath(), 'File 6 false.');
        $this->assertEquals($aFiles[3]['path'], $aActual[1]->getObjectPath(), 'File 3 false.');
        $this->assertEquals($aFiles[4]['path'], $aActual[2]->getObjectPath(), 'File 4 false.');
    }

    /**
     * Test the white list directories.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHandleWhiteListDirectories()
    {
        // Create commit info object.
        $sUser = 'Juliana';
        $sDate = '2010-10-09 09:13:38';
        $sMsg  = '+ A message for the commit';

        $oInfo = new Info('74-1', 0, $sUser, $sDate, $sMsg);

        // Create commit file objects.
        $sBase  = 'hookframework/trunk/filter_directory/';
        $sBase2 = $sBase . 'allowed_dir/';
        $sReal  = 'filter_directory/allowed_dir/';

        // Files.
        $aFiles[0]['path'] = $sBase;
        $aFiles[0]['ext']  = '';
        $aFiles[0]['dir']  = true;
        $aFiles[1]['path'] = $sBase . 'filter_file1.php';
        $aFiles[1]['real'] = $sReal . 'filter_file1.php';
        $aFiles[1]['ext']  = 'php';
        $aFiles[1]['dir']  = false;
        $aFiles[2]['path'] = $sBase . 'filter_file2.php';
        $aFiles[2]['real'] = $sReal . 'filter_file2.php';
        $aFiles[2]['ext']  = 'php';
        $aFiles[2]['dir']  = false;
        $aFiles[3]['path'] = $sBase2 . 'allowed_file1.php';
        $aFiles[3]['real'] = $sReal . 'allowed_file1.php';
        $aFiles[3]['ext']  = 'php';
        $aFiles[3]['dir']  = false;
        $aFiles[4]['path'] = $sBase2 . 'allowed_file2.php';
        $aFiles[4]['real'] = $sReal . 'allowed_file2.php';
        $aFiles[4]['ext']  = 'php';
        $aFiles[4]['dir']  = false;
        $aFiles[5]['path'] = $sBase2 . 'allowed_sub/';
        $aFiles[5]['real'] = $sReal . 'allowed_sub/';
        $aFiles[5]['ext']  = '';
        $aFiles[5]['dir']  = true;
        $aFiles[6]['path'] = $sBase2 . 'allowed_sub/allowed_file1.php';
        $aFiles[6]['real'] = $sReal . 'allowed_sub/allowed_file1.php';
        $aFiles[6]['ext']  = 'php';
        $aFiles[6]['dir']  = false;
        $aFiles[7]['path'] = $sBase . 'filter_file3.php';
        $aFiles[7]['real'] = $sReal . 'filter_file3.php';
        $aFiles[7]['ext']  = 'php';
        $aFiles[7]['dir']  = false;

        // Create the test data.
        $iMax = count($aFiles);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $aParams = array(
                'txn'    => '74-1',
                'rev'    => 0,
                'action' => 'A',
                'item'   => $aFiles[$iFor]['path'],
                'real'   => $aFiles[$iFor]['path'],
                'isdir'  => $aFiles[$iFor]['dir'],
                'ext'    => $aFiles[$iFor]['ext'],
                'props'  => array(),
                'lines'  => null,
                'info'   => $oInfo
            );

            $aObjects[] = new Object($aParams);
        }

        // Create filter object.
        $oObjectFilter = new ObjectFilter();

        // Filter for the style.
        $oObjectFilter->addDirectoryToFilter($sBase);

        // Allow this directory and all its sub directories.
        $oObjectFilter->addDirectoryToWhiteList($sBase2);

        // Create filter and get the filtered objects.
        $oFilter = new Filter($aObjects);
        $aActual = $oFilter->getFilteredFiles($oObjectFilter);

        // Tests.
        $this->assertEquals(4, count($aActual), 'Count false.');
    }

    /**
     * Test for handle directories, when no directory is given.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testHandleNoDirectoryElement()
    {
        // Create commit info object.
        $sUser = 'Juliana';
        $sDate = '2010-10-09 09:13:38';
        $sMsg  = '+ A message for the commit';

        $oInfo = new Info('74-1', 0, $sUser, $sDate, $sMsg);

        // Create commit file objects.
        $sBase  = 'hookframework/trunk/filter_directory/';
        $sBase2 = $sBase . 'allowed_dir/';
        $sReal  = 'filter_directory/allowed_dir/';

        // Files.
        $aFiles[0]['path'] = $sBase;
        $aFiles[0]['ext']  = '';
        $aFiles[0]['dir']  = true;
        $aFiles[1]['path'] = $sBase . 'filter_file1.php';
        $aFiles[1]['real'] = $sReal . 'filter_file1.php';
        $aFiles[1]['ext']  = 'php';
        $aFiles[1]['dir']  = false;
        $aFiles[2]['path'] = $sBase . 'filter_file2.php';
        $aFiles[2]['real'] = $sReal . 'filter_file2.php';
        $aFiles[2]['ext']  = 'php';
        $aFiles[2]['dir']  = false;
        $aFiles[3]['path'] = $sBase2 . 'allowed_file1.php';
        $aFiles[3]['real'] = $sReal . 'allowed_file1.php';
        $aFiles[3]['ext']  = 'php';
        $aFiles[3]['dir']  = false;
        $aFiles[4]['path'] = $sBase2 . 'allowed_file2.php';
        $aFiles[4]['real'] = $sReal . 'allowed_file2.php';
        $aFiles[4]['ext']  = 'php';
        $aFiles[4]['dir']  = false;

        // Create the test data.
        $iMax = count($aFiles);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $aParams = array(
                'txn'    => '74-1',
                'rev'    => 0,
                'action' => 'A',
                'item'   => $aFiles[$iFor]['path'],
                'real'   => $aFiles[$iFor]['path'],
                'isdir'  => $aFiles[$iFor]['dir'],
                'ext'    => $aFiles[$iFor]['ext'],
                'props'  => array(),
                'lines'  => null,
                'info'   => $oInfo
            );

            $aObjects[] = new Object($aParams);
        }

        // Create filter object.
        $oObjectFilter = new ObjectFilter();

        // Allow this directory and all its sub directories.
        $oObjectFilter->addDirectoryToWhiteList($sBase2);

        // Create filter and get the filtered objects.
        $oFilter = new Filter($aObjects);
        $aActual = $oFilter->getFilteredFiles($oObjectFilter);

        // Tests.
        $this->assertEquals(7, count($aActual), 'Count false.');
    }
}
