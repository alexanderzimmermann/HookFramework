<?php
/**
 * Class for parsing the difference lines of a commit.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Commit\Parser;

use Hook\Commit\Diff\Lines as DiffLines;

/**
 * Class for parsing the difference lines of a commit.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Lines
{
	/**
	 * Difference Lines.
	 * @var array
	 */
	private $aLines = array();

	/**
	 * Constructor.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct()
	{
	} // function

	/**
	 * Parse the lines.
	 * @param integer $iId    Represents the index in the file commit stack.
	 * @param array   $aLines The extracted properties lines from the diff.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function parse($iId, array $aLines)
	{
		// Old stuff.
		$sOld = array_shift($aLines);

		// New stuff.
		$sNew = array_shift($aLines);

		$this->aLines[$iId] = new DiffLines($sOld, $sNew, $aLines);
	} // function

	/**
	 * Return the difference lines.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getLines()
	{
		return $this->aLines;
	} // function
} // class