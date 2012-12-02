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
	 * Set the filter stuff.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function setFilter($aFilter)
	{
		// Set filter stuff from configuration.
		foreach ($aFilter['Directory'] as $sDirectory)
		{
			$this->oObjectFilter->addDirectoryToFilter($sDirectory);
		} // foreach

		foreach ($aFilter['Files'] as $sFile)
		{
			$this->oObjectFilter->addFileToFilter($sFile);
		} // foreach

		foreach ($aFilter['WhitelistDirectories'] as $sDirectory)
		{
			$this->oObjectFilter->addDirectoryToWhitelist($sDirectory);
		} // foreach

		foreach ($aFilter['WhitelistFiles'] as $sFile)
		{
			$this->oObjectFilter->addFileToWhitelist($sFile);
		} // foreach
	} // function

	/**
	 * Register the action.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
		// Check that the configuration is set.
		if (true === isset($this->aCfg['Filter']))
		{
			$this->setFilter($this->aCfg['Filter']);
		} // if

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
		$sStandard = $this->aCfg['Standard'];
		$sTabwidth = $this->aCfg['Style']['TabWidth'];

		$aLines    = array();
		$sCommand  = 'phpcs --standard=' . $sStandard . ' --tab-width=' . $sTabwidth . ' ';
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
