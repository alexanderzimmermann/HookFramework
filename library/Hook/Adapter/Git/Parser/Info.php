<?php
/**
 * Parse the information lines of git information's.
 * @category   Adapter
 * @package    Git
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter\Git\Parser;

use Hook\Commit\Info as InfoObject;

/**
 * Parse the information lines of git information's
 * @category   Adapter
 * @package    Git
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class Info
{
    /**
     * Collection information.
     * @var array
     */
    private $aInfo = array();

    /**
     * Constructor.
     * @param string $sSha1 Commit identifier.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct($sSha1)
    {
        $this->aInfo['txn'] = $sSha1;
        $this->aInfo['rev'] = 0;
    }

    /**
     * Parse the user information to parse.
     * @param array $aUser User data.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parseUser(array $aUser)
    {
        // This elements in this order.
        $aProperties = array(
            'user', 'email', 'datetime', 'timezone'
        );

        // At the moment only one line is expected.
        // Example: alexanderzimmermann <alex@azimmermann.com> 1377095938 +0200
        $aUser = explode(' ', array_shift($aUser));
        $iMax  = count($aUser);

        // Discard empty elements. Count could also be 0.
        if ($iMax > 4) {
            $iMax = 4;
        }

        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $this->aInfo[$aProperties[$iFor]] = trim($aUser[$iFor]);
        }

        // Convert timestamp.
        $this->aInfo['datetime'] = strftime('%Y-%m-%d %H:%M:%S', $this->aInfo['datetime']);
    }

    /**
     * Parse the commit message.
     * @param array $aData Message text.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parseMessage(array $aData)
    {
        $this->aInfo['message'] = implode(PHP_EOL, $aData);
    }

    /**
     * Get the info object.
     * @return Info
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getInfoObject()
    {
        $oInfo = new InfoObject(
                    $this->aInfo['txn'],
                    $this->aInfo['rev'],
                    $this->aInfo['user'],
                    $this->aInfo['datetime'],
                    $this->aInfo['message'],
                    $this->aInfo['email'],
                    $this->aInfo['timezone']
        );

        return $oInfo;
    }
}
