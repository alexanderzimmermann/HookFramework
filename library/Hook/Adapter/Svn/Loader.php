<?php
/**
 * Loading the different listener types.
 * @category   Core
 * @package    Listener
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter\Svn;

use \DirectoryIterator;
use Hook\Adapter\LoaderAbstract;

/**
 * Loading the different listener types.
 * There are 3 types of transactions.
 * One for Start (start), before a transaction is started.
 * One after the transaction is stared, but not commit to the repository (pre).
 * One after the transaction is commit to the repository and the work is done (post).
 * <ul>
 * <li>Start</li>
 * <li>Pre</li>
 * <li>Post</li>
 * </ul>
 * @category   Core
 * @package    Listener
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class Loader extends LoaderAbstract
{
    /**
     * Init the Listener Parser.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function init()
    {
        $this->readDirectory();
        $this->checkListener();
        $this->registerListenerInfo();
        $this->registerListenerObject();
    }

    /**
     * Read the files for the actual main hook action in directory.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    protected function readDirectory()
    {
        $sType = ucfirst($this->oArguments->getMainType());

        // If directory does not exists, return.
        if (false === is_dir($this->sPath . $sType)) {
            return;
        } // if

        $oIterator = new \DirectoryIterator($this->sPath . $sType);
        $aListener = array();

        foreach ($oIterator as $oFile) {
            if (true === $oFile->isFile()) {
                if ('php' === $oFile->getExtension()) {
                    $aListener[] = $oFile->getPathname();
                } // if
            } // if
        } // foreach

        $this->aListenerFiles = $aListener;
    }
}
