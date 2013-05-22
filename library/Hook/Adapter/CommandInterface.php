<?php
/**
 * Interface file for the adapter for the git version control system.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter;

use Hook\Adapter;
use Hook\Adapter\Svn\Arguments;

/**
 * Interface file for the adapter for the git version control system.
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
interface CommandInterface
{
    /**
     * Initialize the adapter.
     * @param Arguments $oArguments Command line arguments.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function init(Arguments $oArguments);

    /**
     * Gets the items of a commit that were changed (file, directory list).
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getCommitChanged();

    /**
     * Get information of that commit (user, text message).
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getInfo();

    /**
     * Get the difference of that commit.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getCommitDiff();

    /**
     * Write content from commited file.
     * @param string $sFile    File from TXN.
     * @param string $sTmpFile Temporary file on disk.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getContent($sFile, $sTmpFile);

    /**
     * Get list of properties to the item.
     * @param string $sItem Element for the properties (Directory or file).
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     * @since  1.0.0
     */
    public function getPropertyList($sItem);

    /**
     * Get the property value.
     * @param string $sItem     Element for the properties (Directory or file).
     * @param string $sProperty Name of property of value to get.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     * @since  1.0.0
     */
    public function getPropertyValue($sItem, $sProperty);
} // interface
