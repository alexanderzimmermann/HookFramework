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

namespace Hook\Commit\Diff;

/**
 * Difference in Transaction / Revision.
 * @category   Core
 * @package    Commit
 * @subpackage Diff
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Lines
{
	/**
	 * Old stuff info.
	 * @var string
	 */
	protected $sOld;

	/**
	 * New stuff info.
	 * @var string
	 */
	protected $sNew;

	/**
	 * Zeilen aus dem svn diff.
	 * @var array
	 */
	protected $aLines;

	/**
	 * Constructor.
	 * @param string $sOld   Old stuff info.
	 * @param string $sNew   New stuff info.
	 * @param array  $aLines Lines with the difference.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct($sOld, $sNew, array $aLines)
	{
		$this->sOld   = $sOld;
		$this->sNew   = $sNew;
		$this->aLines = $aLines;
	} // function

	/**
	 * Return the changed lines.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getLines()
	{
		return $this->aLines;
	} // function
} // class