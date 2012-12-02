<?php
/**
 * Difference in Transaction / Revision.
 * @category   Core
 * @package    Commit
 * @subpackage Diff
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Commit\Data\Diff;

use Hook\Commit\Data\Diff\Diff;

/**
 * Difference in Transaction / Revision.
 * @category   Core
 * @package    Commit
 * @subpackage Diff
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
	 * The information (i.e. @@ -32,5 +34,19 @@).
	 * @var Diff
	 */
	protected $oInfo;

	/**
	 * Raw lines from svn diff for this part.
	 * @var array
	 */
	protected $aLines;

	/**
	 * The old lines.
	 * @var array
	 */
	protected $aOldLines = array();

	/**
	 * The new lines.
	 * @var array
	 */
	protected $aNewLines = array();

	/**
	 * Constructor.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct()
	{
	} // function

	/**
	 * Set the change information.
	 * @param Diff The Unified diff information.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setInfo(Diff $oInfo)
	{
		$this->oInfo = $oInfo;
	} // function

	/**
	 * Get the change information
	 * @return Diff
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getInfo()
	{
		return $this->oInfo;
	} // function

	/**
	 * Set the raw lines coming from diff
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setRawLines(array $aLines)
	{
		$this->aLines = $aLines;
	} // function

	/**
	 * Return the changed lines.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getRawLines()
	{
		return $this->aLines;
	} // function

	/**
	 * Sets the lines that were removed in that commit for that file.
	 * @param array $aOldLines The removed lines.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setOldLines(array $aOldLines)
	{
		$this->aOldLines = $aOldLines;
	} // function

	/**
	 * Set the lines that were added in that commit for that file.
	 * @param array $aNewLines The new lines.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setNewLines(array $aNewLines)
	{
		$this->aNewLines = $aNewLines;
	} // function

	/**
	 * Get the removed lines.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getOldLines()
	{
		return $this->aOldLines;
	} // function

	/**
	 * Get the added lines.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getNewLines()
	{
		return $this->aNewLines;
	} // function
} // class