<?php
/**
 * Determines the changed items of a commit (path and or file).
 * @category   Adapter
 * @package    Svn
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter\Svn\Parser;

use Hook\Adapter\ChangedInterface;
use Hook\Adapter\ChangedAbstract;
use Hook\Commit\Object;

/**
 * Determines the changed items of a commit (path and or file).
 * @category   Adapter
 * @package    Svn
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
     * Parse array with the changed items.
     * Determine directory or file.
     * Determine the item action (added, updated or deleted).
     * <ul>
     * <li>A</li>
     * <li>U</li>
     * <li>D</li>
     * </ul>
     * @param array $aLines Items of the commit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parseFiles(array $aLines)
    {
        foreach ($aLines as $sLine) {

            $sObject       = trim($sLine);
            $sActionInfo   = substr($sObject, 0, 4);
            $sObjectAction = trim(str_replace('_', '', $sActionInfo));
            $sObjectAction = strtoupper(substr($sObjectAction, 0, 1));
            $sObject       = str_replace($sActionInfo, '', $sObject);

            // Directory or file.
            // If it is ok, we detect a directory on that / at last position.
            $bIsDir = false;
            if (substr($sObject, -1) === '/') {

                $bIsDir = true;
            }

            $sRealPath = $this->getRealPath($sObject);

            $aTmp = array(
                'txn'    => '',
                'rev'    => '',
                'action' => $sObjectAction,
                'item'   => $sObject,
                'real'   => $sRealPath,
                'ext'    => $this->determineFileExtension($sObject),
                'isdir'  => $bIsDir,
            );

            $this->addItem($aTmp);
        }
    }

    /**
     * Real path (without the common dirs "trunk" and "branches").
     * @param string $sObject Path of object.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getRealPath($sObject)
    {
        // Real path (without the common dirs "trunk" and "branches").
        $aPath = explode('/', $sObject);

        array_shift($aPath);

        // Trunk.
        if ('trunk' === $aPath[0]) {

            array_shift($aPath);
        } else if (('branches' === $aPath[0]) && ('tags' === $aPath[0])) {

            array_shift($aPath);
            array_shift($aPath);

        }

        $sObject = implode('/', $aPath);

        return $sObject;
    }
}
