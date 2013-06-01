<?php
/**
 * Comment.
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

namespace Hook\Adapter\Git\Parser;

use Hook\Adapter\ChangedInterface;
use Hook\Adapter\ChangedAbstract;
use Hook\Commit\Object;

/**
 * Comment.
 * @category   Hook
 * @package    Adapter
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class Changed extends ChangedAbstract implements ChangedInterface
{
    /**
     * Parse the output lines of the diff command.
     * @param array $aLines Changed files from a git diff --raw.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parseFiles(array $aLines)
    {
        foreach ($aLines as $sLine) {

            $sLine  = trim($sLine);
            $aParts = explode(' ', substr($sLine, 1, (strlen($sLine) - 1)));

            $aParts[2] = str_replace('.', '', $aParts[2]);
            $aParts[3] = str_replace('.', '', $aParts[3]);

            if ((1 < strlen($aParts[4])) && (('R' === $aParts[4][0]) || ('C' === $aParts[4][0]))) {

                $aParts['char']    = $aParts[4][0];
                $aParts['percent'] = substr($aParts[4], 1, 2);
                $aParts['source']  = $aParts[6];

            } else {

                $aParts['char']   = $aParts[4];
                $aParts['source'] = $aParts[6];
            }

            $this->addItem($this->createObject($aParts));
        }
    }

    /**
     * Map the values to the internal format and create the object
     * @param array $aData The parsed data.
     * @return Object
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function createObject(array $aData)
    {
        $aValues = array();

        $aValues['isdir']  = false;
        $aValues['txn']    = '';
        $aValues['rev']    = '';
        $aValues['action'] = $aData['char'];
        $aValues['item']   = $aData['source'];
        $aValues['real']   = $aData['source'];
        $aValues['ext']    = $this->determineFileExtension($aData['source']);

        $aValues['info']   = '';
        $aValues['props']  = null;
        $aValues['lines']  = array();

        return $aValues;
    }
}
