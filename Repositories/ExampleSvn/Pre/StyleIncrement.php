<?php
/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace ExampleSvn\Pre;

use Hook\Listener\AbstractObject;
use Hook\Commit\Object;

/**
 * Style Guide Listener.
 * But only for new lines.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class StyleIncrement extends AbstractObject
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
    protected $sListener = 'Style Guide';

    /**
     * Set the filter stuff.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function setFilter($aFilter)
    {
        // Set filter stuff from configuration.
        if (true === isset($aFilter['Directory'])) {
            foreach ($aFilter['Directory'] as $sDirectory) {
                $this->oObjectFilter->addDirectoryToFilter($sDirectory);
            }
        }

        if (true === isset($aFilter['Files'])) {
            foreach ($aFilter['Files'] as $sFile) {
                $this->oObjectFilter->addFileToFilter($sFile);
            }
        }

        if (true === isset($aFilter['WhiteListDirectories'])) {
            foreach ($aFilter['WhiteListDirectories'] as $sDirectory) {
                $this->oObjectFilter->addDirectoryToWhiteList($sDirectory);
            }
        }

        if (true === isset($aFilter['WhiteListFiles'])) {
            foreach ($aFilter['WhiteListFiles'] as $sFile) {
                $this->oObjectFilter->addFileToWhiteList($sFile);
            }
        }
    }

    /**
     * Register the action.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function register()
    {
        // Check that the configuration is set.
        if (true === isset($this->aCfg['Filter'])) {
            $this->setFilter($this->aCfg['Filter']);
        }

        return array(
            'action'     => 'commit',
            'fileaction' => array(
                'A', 'U'
            ),
            'extensions' => array('PHP'),
            'withdirs'   => false
        );
    }

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

        if (empty($aLines) === true) {

            return;
        }

        // React on errors from phpcs.
        // Example: Fatal error: isDeprecated() cannot be called statically in ... on line 58
        if (4 > count($aLines)) {

            return;
        }

        // Parse the code sniffer output.
        $this->parseCodeSnifferErrorLines($aLines);

        // Now we compare the parsed code sniffer lines with the changed lines.
        $aErrorLines = $this->compareChangedLines($oObject);

        $oObject->addErrorLines($aErrorLines);
    }

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
        foreach ($aDiff as $oDiffLines) {
            $aLines = array_merge($aLines, array_keys($oDiffLines->getNewLines()));
        }

        // Now we compare this new lines with the style guide sniff lines.
        // Notice: Only the errors are important in this listener.
        $aErrorLines = array();
        foreach ($this->aCs[self::CS_ERROR] as $iLine => $sMessage) {
            if (false !== in_array($iLine, $aLines)) {
                $aErrorLines[] = $sMessage;
            }
        }

        return $aErrorLines;
    }

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
        foreach ($aLines as $sLine) {
            // Split the parts of the line.
            $aParts = explode(' | ', $sLine);

            // Line number.
            // If line number is 0, then its just another message from line before.
            $iLine = (int)trim($aParts[0]);
            if (0 === $iLine) {
                $this->aCs[$sType][$iLast] .= $sLine . "\n";
                continue;
            }

            // Type of message.
            $sType = trim($aParts[1]);

            // Now we store the line information.
            // Error and Warning are separated. For this listener only errors are important.
            $this->aCs[$sType][$iLine] = $sLine . "\n";

            $iLast = $iLine;
        }
    }
}
