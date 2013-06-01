<?php
/**
 * Filter class that stores directories and files a listener should not process.
 * @category   Core
 * @package    Filter
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Filter;

/**
 * Filter class that stores directories and files a listener should not process.
 * @category   Core
 * @package    Filter
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class ObjectFilter
{
    /**
     * Directories to filter.
     * @var array
     */
    private $aDirectories = array();

    /**
     * Files to filter.
     * @var array
     */
    private $aFiles = array();

    /**
     * Directories that are exceptions in a directory that is filtered.
     * @var array
     */
    private $aWhiteListedDirectories = array();

    /**
     * Files that are exceptions in a directory.
     * @var array
     */
    private $aWhiteListedFiles = array();

    /**
     * Check if it is a file.
     * @param string $sFile Filename that is checked.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function isFile($sFile)
    {
        if (substr($sFile, -1) === '/') {
            return false;
        }

        return true;
    }

    /**
     * Filter a directory that should not be handled.
     * @param string $sDirectory Directory name.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function addDirectoryToFilter($sDirectory)
    {
        if (substr($sDirectory, -1) !== '/') {
            $sDirectory .= '/';
        }

        if (in_array($sDirectory, $this->aDirectories) === false) {
            $this->aDirectories[] = $sDirectory;
        }
    }

    /**
     * Add a file to be filtered and should not processed.
     * @param string $sFile Filename that is filtered (incl. Dir).
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function addFileToFilter($sFile)
    {
        if (in_array($sFile, $this->aFiles) === false) {
            if ($this->isFile($sFile) === true) {
                $this->aFiles[] = $sFile;
            }
        }
    }

    /**
     * Adds a file to the allowed files.
     * @param string $sFile Filename for the exception.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function addFileToWhiteList($sFile)
    {
        // File filter overwrites white list.
        if (in_array($sFile, $this->aFiles) === false) {
            if (in_array($sFile, $this->aWhiteListedFiles) === false) {
                if ($this->isFile($sFile) === true) {
                    $this->aWhiteListedFiles[] = $sFile;
                }
            }
        }
    }

    /**
     * Removes the directory from the filter (recursively).
     * @param string $sDirectory Directory name for the exception.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function addDirectoryToWhiteList($sDirectory)
    {
        if (substr($sDirectory, -1) !== '/') {
            $sDirectory .= '/';
        }

        // Directory filter overwrites white list.
        if (false === in_array($sDirectory, $this->aDirectories)) {
            if (false === in_array($sDirectory, $this->aWhiteListedDirectories)) {
                $this->aWhiteListedDirectories[] = $sDirectory;
            }
        }
    }

    /**
     * Returns list of filtered directories.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getFilteredDirectories()
    {
        return $this->aDirectories;
    }

    /**
     * Returns list of filtered files.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getFilteredFiles()
    {
        return $this->aFiles;
    }

    /**
     * Returns list of allowed directories.
     * @return array
     * @since  1.0.0
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getWhiteListDirectories()
    {
        return $this->aWhiteListedDirectories;
    }

    /**
     * Returns list of allowed files.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getWhiteListFiles()
    {
        return $this->aWhiteListedFiles;
    }
}
