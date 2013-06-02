<?php
/**
 * Parse the information lines of a commit..
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HooK\Adapter\Svn\Parser;

use Hook\Commit\Info as InfoObject;

/**
 * Parse the information lines of a commit.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
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
     * Parse info from the commit.
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
            'user', 'datetime', 'messagelength', 'message'
        );

        $iMax = count($aData);

        // Discard empty elements. Count could also be 0.
        if ($iMax > 4) {
            $iMax = 4;
        }

        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $sData = $aData[$iFor];
            if ($aProperties[$iFor] === 'message') {
                $aData[$iFor] = $this->parseMessage($sData);
            }

            $aInfo[$aProperties[$iFor]] = trim($aData[$iFor]);
        }

        $oInfo = new InfoObject(
            $aInfo['txn'],
            $aInfo['rev'],
            $aInfo['user'],
            $aInfo['datetime'],
            $aInfo['message']
        );

        return $oInfo;
    }

    /**
     * Parse message.
     * @param string $sMessage Commit Text.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function parseMessage($sMessage)
    {
        $aMatches = array();

        // Replace special signs in Format \\123.
        preg_match_all('/\?\\\\\\\\([0-9]+)/', $sMessage, $aMatches);

        $iMax = count($aMatches[0]);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $sChr     = $aMatches[0][$iFor];
            $iChr     = (int)$aMatches[1][$iFor];
            $sMessage = str_replace($sChr, chr($iChr), $sMessage);
        }

        return $sMessage;
    }
}
