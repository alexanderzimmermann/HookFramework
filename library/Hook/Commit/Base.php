<?php
/**
 * Base class for commit data info and object.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Commit;

/**
 * Base class for commit data info and object.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Base
{
    /**
     * Transaction.
     * @var string
     */
    protected $sTxn;

    /**
     * Revision.
     * @var integer
     */
    protected $iRev;

    /**
     * Error lines.
     * @var array
     */
    protected $aErrorLines;

    /**
     * Constructor.
     * @param string  $sTxn Transaction number (666-1).
     * @param integer $iRev Revision number (666).
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct($sTxn, $iRev)
    {
        $this->sTxn = $sTxn;
        $this->iRev = $iRev;

        $this->aErrorLines = array();
    }

    /**
     * Return the trans action number.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getTransaction()
    {
        return $this->sTxn;
    }

    /**
     * Return Revision number.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getRevision()
    {
        return $this->iRev;
    }

    /**
     * Add error message.
     * @param string $sError Error message.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function addError($sError)
    {
        $this->aErrorLines[] = $sError;
    }

    /**
     * Add multiple error messages.
     * @param array $aErrorLines Messages.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function addErrorLines(array $aErrorLines)
    {
        $this->aErrorLines = array_merge($this->aErrorLines, $aErrorLines);
    }

    /**
     * Return error messages and clear them.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getErrorLines()
    {
        $aErrorLines       = $this->aErrorLines;
        $this->aErrorLines = array();

        return $aErrorLines;
    }
}
