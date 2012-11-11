<?php
/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Example\Pre;

use Hook\Commit\Data\Object;
use Hook\Listener\AbstractObject;

/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Style extends AbstractObject
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Style Guide';

	/**
	 * Register the action.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
		$sFilterDirectory = 'tmp/filter/filter_directory/';
		$sWhiteListFile   = $sFilterDirectory . 'filter_file_whitelist.php';

		// Filter for style.
		$this->oObjectFilter->addDirectoryToFilter($sFilterDirectory);
		$this->oObjectFilter->addFileToWhitelist($sWhiteListFile);

		// Ignore test files.
		$sBaseFolder     = 'tmp/newfolder1/newfolder1_1/';
		$sParseErrorFile = $sBaseFolder . 'parse-error_file1.php';
		$this->oObjectFilter->addFileToFilter($sParseErrorFile);

		$sParseErrorFile = $sBaseFolder . 'parse-error_file2.php';
		$this->oObjectFilter->addFileToFilter($sParseErrorFile);

		return array(
				'action'     => 'commit',
				'fileaction' => array(
								 'A', 'U'
								),
				'extensions' => array('PHP'),
				'withdirs'   => false
			   );
	} // function

	/**
	 * Execute the action.
	 * @param Object $oObject Directory / File-Object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(Object $oObject)
	{
		$aLines    = array();
		$sCommand  = 'phpcs --standard=PEAR --tab-width=4 ';
		$sCommand .= $oObject->getTmpObjectPath();

		exec($sCommand, $aLines);

		if (empty($aLines) === true)
		{
			return;
		} // if

		// Error or warning.
		$iResult = $this->determineErrorWarnings($aLines);

		if (($iResult & 1) === 1)
		{
			// Trim empty lines at start and end.
			unset($aLines[0]);
			unset($aLines[1]);
			unset($aLines[2]);

			$oObject->addErrorLines($aLines);
		} // if
	} // function

	/**
	 * Error or Warnings.
	 *
	 * Result comes back as a bit.
	 * 1 = Errors
	 * 2 = Warnings
	 * @param array $aLines Lines from phpcs command.
	 * @return integer
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function determineErrorWarnings(array $aLines)
	{
		$sSummaryLine = $aLines[3];

		$aMatches = array();
		$sPattern = '/([0-9]+) ERROR/i';
		preg_match($sPattern, $sSummaryLine, $aMatches);

		$iErrors = (int) $aMatches[1];

		$aMatches = array();
		$sPattern = '/([0-9]+) WARNING/i';
		preg_match($sPattern, $sSummaryLine, $aMatches);

		$iWarnings = (int) $aMatches[1];

		$iResult = 0;
		if ($iErrors > 0)
		{
			$iResult = 1;
		} // if

		if ($iWarnings > 0)
		{
			$iResult += 2;
		} // if

		return $iResult;
	} // function
} // class
