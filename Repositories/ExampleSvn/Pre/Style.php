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

use Hook\Commit\Object;
use Hook\Listener\AbstractObject;

/**
 * Style Guide Listener.
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
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function processAction(Object $oObject)
    {
        $sStandard = $this->aCfg['Standard'];
        $sTabWidth = $this->aCfg['Style']['TabWidth'];

        $aLines   = array();
        $sCommand = 'phpcs --standard=' . $sStandard . ' --tab-width=' . $sTabWidth . ' ';
        $sCommand .= $oObject->getTmpObjectPath();

        exec($sCommand, $aLines);

        if (empty($aLines) === true) {

            return;
        }

        // React on errors from phpcs.
        // Example: Fatal error: isDeprecated() cannot be called statically in ... on line 58
        if (4 > count($aLines)) {

            return;
        }

        // Error or warning.
        $iResult = $this->determineErrorWarnings($aLines);

        if (($iResult & 1) === 1) {
            // Trim empty lines at start and end.
            unset($aLines[0]);
            unset($aLines[1]);
            unset($aLines[2]);

            $oObject->addErrorLines($aLines);
        }
    }

    /**
     * Error or Warnings.
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

        $iErrors = 0;
        if (true === isset($aMatches[1])) {

            $iErrors = (int) $aMatches[1];
        }

        $aMatches = array();
        $sPattern = '/([0-9]+) WARNING/i';
        preg_match($sPattern, $sSummaryLine, $aMatches);

        $iWarnings = 0;
        if (true === isset($aMatches[1])) {

            $iWarnings = (int) $aMatches[1];
        }

        $iResult = 0;
        if ($iErrors > 0) {
            $iResult = 1;
        }

        if ($iWarnings > 0) {
            $iResult += 2;
        }

        return $iResult;
    }
}
