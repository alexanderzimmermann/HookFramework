<?php
/**
 * A simple response object.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Core;

use Hook\Commit\Info;
use Hook\Commit\Object;
use Hook\Listener\AbstractInfo;
use Hook\Listener\AbstractObject;

/**
 * A simple response object.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class Response
{
    /**
     * Stream output goes to.
     * @var resource
     */
    private $rStream = STDERR;

    /**
     * The response result.
     * @var integer
     */
    private $iResult = 1;

    /**
     * The text to be send to the output.
     * @var string
     */
    private $sText = 'no response text given or exit is ok.';

    /**
     * The response lines from the object listener.
     * @var array
     */
    private $aLines = array();

    /**
     * Error lines for the Info elements.
     * @var array
     */
    private $aInfoLines = array();

    /**
     * Constructor.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct($rStream = STDERR)
    {
        $this->rStream = $rStream;
    }

    /**
     * Set the result. Only 0 and 1 at the moment. Other values will be ignored.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setResult($iResult)
    {
        // As 0 stands for success we want to be save that a rue 0 is given.
        if (false === is_int($iResult)) {

            $iResult = 1;
        }

        $iResult = (int) $iResult;

        if ((0 === $iResult) || (1 === $iResult)) {

            $this->iResult = $iResult;
        }
    }

    /**
     * Get the result to be send to the VCS.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getResult()
    {
        return $this->iResult;
    }

    /**
     * Set the text.
     * @param string $sText The text to be send.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setText($sText)
    {
        $this->sText = $sText;
    }

    /**
     * Get the text.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getText()
    {
        return $this->sText;
    }

    /**
     * Print listener header.
     * @param string $sListener Listener Name.
     * @return string
     */
    private function getListenerHeader($sListener)
    {
        $sMessage  = '/' . str_repeat('+', (strlen($sListener) + 10)) . "\n";
        $sMessage .= ' +  HOOK: ' . $sListener . "\n\n";

        return $sMessage;
    }

    /**
     * Format the info listener error messages.
     * @param Info          $oInfo     Commit Info Object that is processed.
     * @param AbstractInfo $oListener Listener that is processed.
     * @return void
     */
    public function processActionInfo(Info $oInfo, AbstractInfo $oListener)
    {
        $aLines = $oInfo->getErrorLines();

        if (empty($aLines) === false) {
            if (isset($this->aInfoLines) === false) {
                $this->aInfoLines = array();
            }

            $this->aInfoLines[] = $this->getListenerHeader($oListener->getListenerName());
            $this->aInfoLines   = array_merge($this->aInfoLines, $aLines);

            $this->iResult = 1;
        }
    }

    /**
     * Format the object listener error messages.
     * @param Object         $oObject   Actual File Object that is processed.
     * @param AbstractObject $oListener Listener that is processed.
     * @return void
     */
    public function processActionObject(Object $oObject, AbstractObject $oListener)
    {
        $aLines = $oObject->getErrorLines();

        if (false === empty($aLines)) {
            $sFile = $oObject->getObjectPath();
            if (false === isset($this->aLines[$sFile])) {
                $this->aLines[$sFile] = array();
            } else {
                $this->aLines[$sFile][] = "\n";
            }

            $this->aLines[$sFile][] = $this->getListenerHeader($oListener->getListenerName());
            $this->aLines[$sFile]   = array_merge($this->aLines[$sFile], $aLines);

            $this->iResult = 1;
        }
    }

    /**
     * Return messages that occurred so far, after that clear messages.
     * @return string
     */
    public function getMessages()
    {
        if ((true === empty($this->aLines)) &&
            (true === empty($this->aInfoLines))) {

            $this->iResult = 0;
            $this->sText   = '';
            return '';
        }

        // Marks the start of the text that will be shown.
        $this->sText = "\n";

        // First the Info listener lines.
        if (false === empty($this->aInfoLines)) {
            $this->sText .= implode("\n", $this->aInfoLines);
        }

        // Listener lines for the files.
        if (false === empty($this->aLines)) {
            $bPrintLine = false;
            foreach ($this->aLines as $sFile => $aFileLines) {
                if (true === $bPrintLine) {
                    $this->sText .= "\n\n";
                }

                $this->sText .= '/' . str_repeat('*', (strlen($sFile) + 10)) . "\n";
                $this->sText .= ' *  FILE: ' . $sFile . "\n\n";

                $this->sText .= implode("\n", $aFileLines);
                $this->sText .= str_repeat('=', 80) . "\n";

                $bPrintLine = true;
            }
        }

        $this->sText .= "\n\n";

        $this->aLines     = array();
        $this->aInfoLines = array();
        $this->iResult    = 1;

        return $this->sText;
    }

    /**
     * Send the text to the VCS.
     * @return void
     */
    public function send()
    {
        fwrite($this->rStream, $this->sText);
    }
}
