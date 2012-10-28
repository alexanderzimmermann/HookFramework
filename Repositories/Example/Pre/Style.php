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

use Core\Commit\CommitObject;
use Core\Listener\ListenerObjectAbstract;

/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Style extends ListenerObjectAbstract
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Style Guide';

	/**
	 * Registrieren auf die Aktion.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
		$sFilterDirectory = 'trunk/tmp/filter/filter_directory/';
		$sWhiteListFile   = $sFilterDirectory . 'filter_file_whitelist.php';

		// Filter fuer den Style.
		$this->oObjectFilter->addDirectoryToFilter($sFilterDirectory);
		$this->oObjectFilter->addFileToWhitelist($sWhiteListFile);

		// Testdateien ignorieren.
		$sBaseFolder     = 'trunk/tmp/newfolder1/newfolder1_1/';
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
	 * Ausfuehren der Aktion.
	 * @param CommitObject $oObject Verz. / Datei-Objekt.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitObject $oObject)
	{
		$aLines    = array();
		$sCommand  = 'phpcs --standard=PEAR --tab-width=4 ';
		$sCommand .= $oObject->getTmpObjectPath();

		exec($sCommand, $aLines);

		if (empty($aLines) === true)
		{
			return;
		} // if

		// Fehler oder Warnings.
		$iResult = $this->determineErrorWarnings($aLines);

		if (($iResult & 1) === 1)
		{
			// Leerzeile am Schluss und am Anfang entfernen.
			unset($aLines[0]);
			unset($aLines[1]);
			unset($aLines[2]);

			$oObject->addErrorLines($aLines);
		} // if
	} // function

	/**
	 * Fehler oder Warnings.
	 *
	 * Ergebnis kommt als Bit zurueck.
	 * 1 = Fehler
	 * 2 = Warnings
	 * @param array $aLines Zeilen aus phpcs Command.
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
