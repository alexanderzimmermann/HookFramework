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
     * Parse the information.
     * @param array   $aData Commit info lines.
     * @param string  $sTxn  Transaction identifier.
     * @param integer $iRev  Revision number.
     * @internal param string $sRev Revision identifier.
     * @return InfoObject
     * @author   Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parse(array $aData, $sTxn, $iRev)
    {
        // Set defaults.
        $aInfo             = array();
        $aInfo['txn']      = $sTxn;
        $aInfo['rev']      = $iRev;
        $aInfo['user']     = '';
        $aInfo['datetime'] = '';
        $aInfo['message']  = '';

        // This elements in this order.
        $aProperties = array(
                        'user', 'email', 'datetime', 'timezone'
                       );

        // At the moment only one line is expected.
        // Example: alexanderzimmermann <alex@azimmermann.com> 1377095938 +0200
        $aData = explode(' ', array_shift($aData));
        $iMax  = count($aData);

        // Discard empty elements. Count could also be 0.
        if ($iMax > 4) {
            $iMax = 4;
        }

        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $aInfo[$aProperties[$iFor]] = trim($aData[$iFor]);
        }

        // Convert timestamp.
        $aInfo['datetime'] = strftime('%Y-%m-%d %H:%M:%S', $aInfo['datetime']);

        $oInfo = new InfoObject(
            $aInfo['txn'],
            $aInfo['rev'],
            $aInfo['user'],
            $aInfo['datetime'],
            $aInfo['email'],
            $aInfo['timezone']
        );

        return $oInfo;
    }
}
