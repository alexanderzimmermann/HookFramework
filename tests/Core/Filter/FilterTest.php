<?php
/**
 * Filter Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Filter
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'Core/Commit/CommitInfo.php';
require_once 'Core/Commit/CommitObject.php';
require_once 'Core/Filter/Filter.php';
require_once 'Core/Filter/ObjectFilter.php';

/**
 * Filter Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Filter
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class FilterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test add Directory to filter.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public function testFilter()
	{
		// Create commit info object.
		$sUser = 'testuser';
		$sDate = '2008-12-30 09:13:38';
		$sMsg  = '+ a comment';

		$oInfo = new CommitInfo('74-1', 0, $sUser, $sDate, $sMsg);

		// Create commit file objects.
		$sBase  = 'trunk/tmp/filter/filter_directory/';
		$sBase2 = 'trunk/tmp/newfolder1/newfolder1_1/';

		$aFiles[0]['path'] = $sBase;
		$aFiles[0]['dir']  = true;
		$aFiles[0]['xn']   = 'A';
		$aFiles[1]['path'] = $sBase . 'filter_file1.php';
		$aFiles[1]['dir']  = false;
		$aFiles[1]['xn']   = 'A';
		$aFiles[2]['path'] = $sBase . 'filter_file2.php';
		$aFiles[2]['dir']  = false;
		$aFiles[2]['xn']   = 'A';
		$aFiles[3]['path'] = $sBase2 . 'correct_file1.php';
		$aFiles[3]['dir']  = false;
		$aFiles[3]['xn']   = 'U';
		$aFiles[4]['path'] = $sBase2 . 'correct_file2.php';
		$aFiles[4]['dir']  = false;
		$aFiles[4]['xn']   = 'U';
		$aFiles[5]['path'] = $sBase2 . 'parse-error_file1.php';
		$aFiles[5]['dir']  = false;
		$aFiles[5]['xn']   = 'U';
		$aFiles[6]['path'] = $sBase . 'filter_file_whitelist.php';
		$aFiles[6]['dir']  = false;
		$aFiles[6]['xn']   = 'A';

		$iMax = count($aFiles);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$aParams = array(
						'txn'    => '74-1',
						'rev'    => 0,
						'action' => $aFiles[$iFor]['xn'],
						'item'   => $aFiles[$iFor]['path'],
						'isdir'  => $aFiles[$iFor]['dir'],
						'props'  => array(),
						'lines'  => null,
						'info'   => $oInfo
					   );

			$aObjects[] = new CommitObject($aParams);
		} // for

		// Create filterobject.
		$oObjectFilter = new ObjectFilter();

		$sFilterDirectory = 'trunk/tmp/filter/filter_directory/';
		$sWhiteListFile   = $sFilterDirectory . 'filter_file_whitelist.php';

		// Filter for the style.
		$oObjectFilter->addDirectoryToFilter($sFilterDirectory);
		$oObjectFilter->addFileToWhitelist($sWhiteListFile);

		// Ignore testfiles.
		$sParseErrorFile = $sBase2 . 'parse-error_file1.php';
		$oObjectFilter->addFileToFilter($sParseErrorFile);

		// Yes, this file doesn't exists.
		$sParseErrorFile = $sBase2 . 'parse-error_file2.php';
		$oObjectFilter->addFileToFilter($sParseErrorFile);

		// Create filter.
		$oFilter = new Filter($aObjects);
		$aActual = $oFilter->getFilteredFiles($oObjectFilter);

		// Tests.
		$this->assertEquals(3, count($aActual), 'Count false.');
		$this->assertEquals($aFiles[6]['path'], $aActual[0]->getObjectPath(), 'File 6 false.');
		$this->assertEquals($aFiles[3]['path'], $aActual[1]->getObjectPath(), 'File 3 false.');
		$this->assertEquals($aFiles[4]['path'], $aActual[2]->getObjectPath(), 'File 4 false.');
	} // function

	/**
	 * Test the white list directories.
	 * @return void
	 * @author Alexander Zimmermann <alex@zimmemann.com>
	 */
	public function testHandleWhiteListDirectories()
	{
		// Create commit info object.
		$sUser = 'testuser';
		$sDate = '2010-10-09 09:13:38';
		$sMsg  = '+ Eine Testmeldung';

		$oInfo = new CommitInfo('74-1', 0, $sUser, $sDate, $sMsg);

		// Erstellen der Commit Dateiobjekte.
		$sBase  = 'trunk/filter_directory/';
		$sBase2 = $sBase . 'allowed_dir/';

		// Files.
		$aFiles[0]['path'] = $sBase;
		$aFiles[0]['dir']  = true;
		$aFiles[1]['path'] = $sBase . 'filter_file1.php';
		$aFiles[1]['dir']  = false;
		$aFiles[2]['path'] = $sBase . 'filter_file2.php';
		$aFiles[2]['dir']  = false;
		$aFiles[3]['path'] = $sBase2 . 'allowed_file1.php';
		$aFiles[3]['dir']  = false;
		$aFiles[4]['path'] = $sBase2 . 'allowed_file2.php';
		$aFiles[4]['dir']  = false;
		$aFiles[5]['path'] = $sBase2 . 'allowed_sub/';
		$aFiles[5]['dir']  = true;
		$aFiles[6]['path'] = $sBase2 . 'allowed_sub/allowed_file1.php';
		$aFiles[6]['dir']  = false;
		$aFiles[7]['path'] = $sBase . 'filter_file3.php';
		$aFiles[7]['dir']  = false;

		// Create the test data.
		$iMax = count($aFiles);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$aParams = array(
						'txn'    => '74-1',
						'rev'    => 0,
						'action' => 'A',
						'item'   => $aFiles[$iFor]['path'],
						'isdir'  => $aFiles[$iFor]['dir'],
						'props'  => array(),
						'lines'  => null,
						'info'   => $oInfo
					   );

			$aObjects[] = new CommitObject($aParams);
		} // for

		// Create filterobject.
		$oObjectFilter = new ObjectFilter();

		// Filter for the style.
		$oObjectFilter->addDirectoryToFilter($sBase);

		// Allow this directory and all its sub directories.
		$oObjectFilter->addDirectoryToWhitelist($sBase2);

		// Create filter and get the filtered objects.
		$oFilter = new Filter($aObjects);
		$aActual = $oFilter->getFilteredFiles($oObjectFilter);


//var_dump($aActual);exit;

		// Tests.
		$this->assertEquals(4, count($aActual), 'Count false.');

	} // function
} // class
