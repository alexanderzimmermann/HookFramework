<?php
/**
 * Class for the common information of a commit (User, Text, Date).
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.1.0
 */

namespace Hook\Commit;

use Hook\Commit\Base;

/**
 * Class for the common information of a commit (User, Text, Date).
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 2.1.0
 */
class Info extends Base
{
    /**
     * Username.
     * @var string
     */
    private $sUser;

    /**
     * Date time.
     * @var string
     */
    private $sDateTime;

    /**
     * Text message of commit.
     * @var string
     */
    private $sMessage;

    /**
     * Lists of objects of this commit.
     * @var array
     */
    private $aObjects;

    /**
     * Constructor.
     * @param string $sTxn      Transaction, if it is a pre commit.
     * @param string $iRev      Revision (if available).
     * @param string $sUser     Username of commit.
     * @param string $sDateTime Date time of commit.
     * @param string $sMessage  Text message of commit.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct($sTxn, $iRev, $sUser, $sDateTime, $sMessage)
    {
        parent::__construct($sTxn, $iRev);
        $this->sUser     = $sUser;
        $this->sDateTime = $sDateTime;
        $this->sMessage  = $sMessage;
        $this->aObjects  = array();
    }

    /**
     * Sets the list of commited objects.
     * @param array $aObjects List of objects.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setObjects(array $aObjects)
    {
        if (empty($this->aObjects) === true) {
            $this->aObjects = $aObjects;
        }
    }

    /**
     * Return user.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getUser()
    {
        return $this->sUser;
    }

    /**
     * Return Date time.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getDateTime()
    {
        return $this->sDateTime;
    }

    /**
     * Return text message of commit.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getMessage()
    {
        return $this->sMessage;
    }

    /**
     * List of objects of commit.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getObjects()
    {
        return $this->aObjects;
    }
}
