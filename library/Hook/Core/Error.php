<?php
/**
 * Error Object for the error messages from the listeners and the output.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Core;

use Hook\Commit\Info;
use Hook\Commit\Object;

/**
 * Error Object for the error messages from the listeners and the output.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Error
{
    /**
     * Error lines for files.
     * @var array
     */
    private $aLines;

    /**
     * Error lines for the Info elements.
     * @var array
     */
    private $aInfoLines;

    /**
     * Standard error lines of other errors.
     * @var array
     */
    private $aCommonLines;

    /**
     * Switch if errors messages are available.
     * @var boolean
     */
    private $bError;

    /**
     * Standard error switch.
     * @var boolean
     */
    private $bCommonError;

    /**
     * Actual Listener.
     * @var string
     */
    private $sListener;

    /**
     * Constructor.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct()
    {
        $this->aLines = array();
        $this->bError = false;

        $this->aCommonLines = array();
        $this->bCommonError = false;
    }

    /**
     * Set the Listener names in Array.
     * @param string $sName Listener Name.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setListener($sName)
    {
        $this->sListener = $sName;
    }

    /**
     * Format the info listener error messages.
     * @param Info $oInfo Commit Info Object.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function processActionInfo(Info $oInfo)
    {
        $aLines = $oInfo->getErrorLines();

        if (empty($aLines) === false) {
            if (isset($this->aInfoLines) === false) {
                $this->aInfoLines = array();
            }

            $this->aInfoLines[] = $this->sListener;
            $this->aInfoLines[] = str_repeat('=', 80);

            $this->aInfoLines = array_merge($this->aInfoLines, $aLines);

            $this->bError = true;
        }
    }

    /**
     * Format the object listener error messages.
     * @param \Hook\Commit\Object|Object $oObject Actual File Object that is processed.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function processActionObject(Object $oObject)
    {
        $aLines = $oObject->getErrorLines();

        if (empty($aLines) === false) {
            $sFile = $oObject->getObjectPath();
            if (false === isset($this->aLines[$sFile])) {
                $this->aLines[$sFile] = array();
            }

            $this->aLines[$sFile][] = $this->sListener;
            $this->aLines[$sFile][] = str_repeat('=', 80);

            $this->aLines[$sFile] = array_merge($this->aLines[$sFile], $aLines);

            $this->bError = true;
        }
    }

    /**
     * Add an error message.
     * @param string $sMessage Text for error message.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function addError($sMessage)
    {
        $this->bCommonError   = true;
        $this->aCommonLines[] = $sMessage;
    }

    /**
     * Add error lines.
     * @param array $aLines Error lines.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function addErrorLines(array $aLines)
    {
        $this->bCommonError = true;
        $this->aCommonLines = array_merge($this->aCommonLines, $aLines);
    }

    /**
     * Return messages that occurred so far, after that clear messages.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getMessages()
    {
        if ((empty($this->aLines) === true) &&
            (empty($this->aInfoLines) === true)
        ) {
            return '';
        }

        $sMessage = "\n\n" . str_repeat('~', 80) . "\n";

        // First the Info listener lines.
        if (empty($this->aInfoLines) === false) {
            $sMessage .= implode("\n", $this->aInfoLines);
        }

        // Listener lines for the files.
        if (empty($this->aLines) === false) {
            $bPrintLine = false;
            foreach ($this->aLines as $sFile => $aFileLines) {
                if ($bPrintLine === true) {
                    $sMessage .= "\n\n";
                }

                $sMessage .= $sFile . "\n";
                $sMessage .= str_repeat('-', 80) . "\n";
                $sMessage .= implode("\n", $aFileLines);

                $bPrintLine = true;
            }
        }

        $sMessage .= "\n\n";
        $sMessage .= str_repeat('~', 80) . "\n";

        $this->aLines = array();
        $this->bError = false;

        return $sMessage;
    }

    /**
     * Return standard error messages.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getCommonMessages()
    {
        $sMessage = implode("\n", $this->aCommonLines);

        $this->aCommonLines = array();
        $this->bCommonError = false;

        return $sMessage;
    }

    /**
     * Are there any error messages.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function hasError()
    {
        return $this->bError;
    }

    /**
     * Standard messages available.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function hasCommonError()
    {
        return $this->bCommonError;
    }
}
