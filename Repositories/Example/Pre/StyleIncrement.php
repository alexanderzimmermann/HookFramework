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
use Hook\Commit\Data\Diff;

use Example\Pre\Style;

require_once __DIR__ . '/Style.php';

/**
 * Style Guide Listener.
 *
 * But only for new lines.
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
class StyleIncrement extends Style
{
	/**
	 * Code sniffer error identifier text.
	 * @var string
	 */
	const CS_ERROR = 'ERROR';

	/**
	 * Code sniffer warning identifier text.
	 * @var string
	 */
	const CS_WARNING = 'WARNING';

	/**
	 * The information from the phpcs command line..
	 * @var array
	 */
	protected $aCs = array();

	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Style Guide Increment';

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
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(Object $oObject)
	{
		$sStandard = $this->aCfg['Standard'];

		$aLines   = array();
		$sCommand = 'phpcs --standard=' . $sStandard . ' ' . $oObject->getTmpObjectPath();

		exec($sCommand, $aLines);

		if (empty($aLines) === true)
		{
			return;
		} // if

		// Parse the code sniffer output.
		$this->parseCodeSnifferErrorLines($aLines);

		// Now we compare the parsed code sniffer lines with the changed lines.
		$aErrorLines = $this->compareChangedLines($oObject);

		$oObject->addErrorLines($aErrorLines);
	} // function

	/**
	 * Compare the parsed code sniffer lines with the changed lines and add errors if necessary.
	 * @param Object $oObject The object passed from processAction.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function compareChangedLines(Object $oObject)
	{
		$aDiff  = $oObject->getChangedParts();
		$aLines = array();

		// First we collect the line numbers of the new lines.
		// The parser gives us the new lines already as the index in the array.
		foreach ($aDiff as $oDiffLines)
		{
			$aLines = array_merge($aLines, array_keys($oDiffLines->getNewLines()));
		} // foreach

		// Now we compare this new lines with the style guide sniff lines.
		// Notice: Only the errors are important in this listener.
		$aErrorLines = array();
		foreach ($this->aCs[self::CS_ERROR] as $iLine => $sMessage)
		{
			if (false !== in_array($iLine, $aLines))
			{
				$aErrorLines[] = $sMessage;
			} // if
		} // foreach

		return $aErrorLines;
	} // function

	/**
	 * Split the code sniffer error lines into ERROR and WARNING and then the lines.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	protected function parseCodeSnifferErrorLines(array $aLines)
	{
		// Slice the array to its messages.
		// Remove 5 headlines and 4 on the end but store them for further use.
		$iCount = count($aLines);
		$aLines = array_slice($aLines, 5, ($iCount - 9));

		// Just in case.
		$sType = '';
		$iLast = 0;

		// Now we analyze the lines.
		foreach ($aLines as $sLine)
		{
			// Split the parts of the line.
			$aParts = explode(' | ', $sLine);

			// Line number.
			// If line number is 0, then its just another message from line before.
			$iLine = (int) trim($aParts[0]);
			if (0 === $iLine)
			{
				$this->aCs[$sType][$iLast] .= $sLine . "\n";
				continue;
			} // if

			// Type of message
			$sType = trim($aParts[1]);

			// Now we store the line information.
			// Error and Warning are separated. For this listener only errors are important.
			$this->aCs[$sType][$iLine] = $sLine . "\n";

			$iLast = $iLine;
		} // foreach
	} // function
} // class
