<?php
/**
 * Filter class that stores directories and files a listener should not process.
 * @category   Core
 * @package    Filter
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Filter class that stores directories and files a listener should not process.
 * @category   Core
 * @package    Filter
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
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
	private $aWhitelistedDirectories = array();

	/**
	 * Files that are exceptions in a directoy.
	 * @var array
	 */
	private $aWhitelistedFiles = array();

	/**
	 * Check if it is a file.
	 * @param string $sFile Filename that is checked.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function isFile($sFile)
	{
		if (substr($sFile, -1) === '/')
		{
			return false;
		} // if

		return true;
	} // function

	/**
	 * Filter a directory that should not be handled.
	 * @param string $sDirectory Directory name.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addDirectoryToFilter($sDirectory)
	{
		if (substr($sDirectory, -1) !== '/')
		{
			$sDirectory .= '/';
		} // if

		if (in_array($sDirectory, $this->aDirectories) === false)
		{
			$this->aDirectories[] = $sDirectory;
		} // if
	} // function

	/**
	 * Add a file to be filtered and should not processed.
	 * @param string $sFile Dateiname der gefiltert wird (inkl. Dir).
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addFileToFilter($sFile)
	{
		if (in_array($sFile, $this->aFiles) === false)
		{
			if ($this->isFile($sFile) === true)
			{
				$this->aFiles[] = $sFile;
			} // if
		} // if
	} // function

	/**
	 * Adds a file to the allowed files.
	 * @param string $sFile Filename for the exception.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addFileToWhitelist($sFile)
	{
		// Filefilter overwrites whitelist.
		if (in_array($sFile, $this->aFiles) === false)
		{
			if (in_array($sFile, $this->aWhitelistedFiles) === false)
			{
				if ($this->isFile($sFile) === true)
				{
					$this->aWhitelistedFiles[] = $sFile;
				} // if
			} // if
		} // if
	} // function

	/**
	 * Removes the directory from the filter (recursively).
	 * @param string $sDirectory Directory name for the exception.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addDirectoryToWhitelist($sDirectory)
	{
		if (substr($sDirectory, -1) !== '/')
		{
			$sDirectory .= '/';
		} // if

		// Directoryfilter overwrites whitelist.
		if (false === in_array($sDirectory, $this->aDirectories))
		{
			if (false === in_array($sDirectory, $this->aWhitelistedDirectories))
			{
				$this->aWhitelistedDirectories[] = $sDirectory;
			} // if
		} // if
	} // function

	/**
	 * Returns list of filtered directories.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getFilteredDirectories()
	{
		return $this->aDirectories;
	} // function

	/**
	 * Returns list of filtered files.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getFilteredFiles()
	{
		return $this->aFiles;
	} // function

	/**
	 * Returns list of allowed directories.
	 * @return array
	 * @since  1.0.0
	 * @author Alexander Zimmermann <alexander.zimmermann@twt.de>
	 */
	public function getWhiteListDirectories()
	{
		return $this->aWhitelistedDirectories;
	} // function

	/**
	 * Returns liste of allowed files.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getWhiteListFiles()
	{
		return $this->aWhitelistedFiles;
	} // function
} // class
