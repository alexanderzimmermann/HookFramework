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
     * Cosntructor
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
     * Send the text to the VCS.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function send()
    {
        fwrite($this->rStream, $this->sText);
    }
}
