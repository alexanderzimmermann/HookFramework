<?php
/**
 * Commit Diff Tests.
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

require_once dirname(__FILE__) . '/../../../TestHelper.php';

// CommitDiffParser.
require_once 'Core/Commit/Parser/DiffParser.php';

/**
 * Commit Diff Tests.
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
class DiffParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Parser Objekt.
	 * @var DiffParser
	 */
	private $oParser;

	/**
	 * Set Up Methode.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setUp()
	{
	} // function

	/**
	 * Set Up der Hilfsdaten.
	 * @param string $sTxn Transaction Nummer des Tests.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getData($sTxn)
	{
		$sFile    = dirname(__FILE__) . '/../../../_files/txn/' . $sTxn;
		$sFile   .= '/changed.txt';
		$sChanged = file_get_contents($sFile);
		$aChanged = explode("\n", $sChanged);

		$iMax = count($aChanged);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$aChanged[$iFor] = substr($aChanged[$iFor], 4);

			if (substr($aChanged[$iFor], -1) === '/')
			{
				$aChanged[$iFor] = substr($aChanged[$iFor], 0, -1);
			} // if
		} // for

		$sFile  = dirname(__FILE__) . '/../../../_files/txn/' . $sTxn;
		$sFile .= '/diff.txt';
		$sDiff  = file_get_contents($sFile);
		$aDiff  = explode("\n", $sDiff);

		$this->oParser = new DiffParser($aChanged, $aDiff);
	} // function

	/**
	 * Test for directories, files and their properties.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testCommit110MultipleData()
	{
		$this->getData('110-1');
		$this->oParser->parse();

		$this->assertSame(23, count($this->oParser->getLines()), 'Lines count wrong.');
		$this->assertSame(10, count($this->oParser->getProperties()), 'Properties count wrong.');
	} // function

	/**
	 * Test diff with a binary file.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testBinaerDiff()
	{
		$this->getData('1719-1');
		$this->oParser->parse();

		$aLines = $this->oParser->getLines();

		$this->assertSame(1, count($aLines), 'Lines count wrong.');
		$this->assertSame(0, count($this->oParser->getProperties()), 'Properties count wrong.');
	} // function

	/**
	 * Test of mixed differences with property settings and files properties.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testMixedPropSetDiff()
	{
		$this->getData('140-1');
		$this->oParser->parse();

		$iLines = count($this->oParser->getLines());
		$this->assertEquals(5, $iLines, 'Lines count wrong.');

		$aProperties = $this->oParser->getProperties();
		$iProperties = count($aProperties);
		$this->assertEquals(2, $iProperties, 'Properties count wrong.');

		// Check the value.
		$sExpected = "mail.log\ncommon.log\ntest.log";
		$oProperty = $aProperties[3]['svn:ignore'];

		$this->assertSame($sExpected, $oProperty->getNewValue(), 'Wrong new value.');
	} // function

	/**
	 * Testen wenn nur Properties gesetzt wurden.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testOnlyPropSetDiff()
	{
		$this->getData('1749-1');
		$this->oParser->parse();

		$this->assertSame(0, count($this->oParser->getLines()), 'Lines count wrong.');
		$this->assertSame(1, count($this->oParser->getProperties()), 'Properties count wrong.');
	} // function

	/**
	 * Test with an other svn version.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testWithSvn166()
	{
		$aDiff[]  = 'Modified: trunk/tmp/newfolder1/newfolder1_1/correct_file1.php';
		$aDiff[]  = '===================================================================';
		$aDiff[]  = '--- trunk/tmp/newfolder1/newfolder1_1/correct_file1.php	2010-09-14 21:29:59 UTC (rev 178)';
		$aDiff[]  = '+++ trunk/tmp/newfolder1/newfolder1_1/correct_file1.php	2010-09-29 07:12:53 UTC (txn 178-6l)';
		$aDiff[]  = '@@ -7,8 +7,8 @@';
		$aDiff[]  = '  * @category   Tmp';
		$aDiff[]  = '  * @package    Test';
		$aDiff[]  = '  * @subpackage Newfolder';
		$aDiff[]  = '- * @author     Alexander Zimmermann <zimmermann.alexander@web.de>';
		$aDiff[]  = '- * @copyright  2008-2009 Alexander Zimmermann <zimmermann.alexander@web.de>';
		$aDiff[]  = '+ * @author     Alexander Zimmermann <alex@azimmermann.com>';
		$aDiff[] = '+ * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>';
		$aDiff[] = '  * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License';
		$aDiff[] = '  * @version    SVN: $Id$';
		$aDiff[] = '  * @link       http://www.azimmermann.com/';
		$aDiff[] = '@@ -23,8 +23,8 @@';
		$aDiff[] = '  * @category   Tmp';
		$aDiff[] = '  * @package    Test';
		$aDiff[] = '  * @subpackage Newfolder';
		$aDiff[] = '- * @author     Alexander Zimmermann <zimmermann.alexander@web.de>';
		$aDiff[] = '- * @copyright  2008-2009 Alexander Zimmermann <zimmermann.alexander@web.de>';
		$aDiff[] = '+ * @author     Alexander Zimmermann <alex@azimmermann.com>';
		$aDiff[] = '+ * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>';
		$aDiff[] = '  * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License';
		$aDiff[] = '  * @version    Release: 1.0.0';
		$aDiff[] = '  * @link       http://www.azimmermann.com/';
		$aDiff[] = '@@ -34,7 +34,7 @@';
		$aDiff[] = ' 	/**';
		$aDiff[] = ' 	 * Konstrutkor.';
		$aDiff[] = ' 	 *';
		$aDiff[] = '-	 * @author Alexander Zimmermann <zimmermann.alexander@web.de>';
		$aDiff[] = '+	 * @author Alexander Zimmermann <alex@azimmermann.com>';
		$aDiff[] = ' 	 */';
		$aDiff[] = ' 	public function __construct()';
		$aDiff[] = ' 	{';
		$aDiff[] = '';
		$aDiff[] = '';
		$aDiff[] = 'Property changes on: trunk/tmp/newfolder1/newfolder1_1/correct_file1.php';
		$aDiff[] = '___________________________________________________________________';
		$aDiff[] = 'Modified: svn:keywords';
		$aDiff[] = '   - Id';
		$aDiff[] = '   + Id Author Date Revision';
		$aDiff[] = '';

		$aChanged = array('trunk/tmp/newfolder1/newfolder1_1/correct_file1.php');

		$this->oParser = new DiffParser($aChanged, $aDiff);
		$this->oParser->parse();

		$aProperties = $this->oParser->getProperties();
		$aLines      = $this->oParser->getLines();

		$this->assertSame(1, count($aProperties), 'Wrong property count.');
		$this->assertSame(1, count($aLines), 'Wrong lines count.');
	} // function
} // class