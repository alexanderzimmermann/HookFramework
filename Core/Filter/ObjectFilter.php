<?php
/**
 * Filter Klasse fuer die Dateien fuer die Listener zu filtern.
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
 * Filter Klasse fuer die Dateien fuer die Listener zu filtern.
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
	 * Verzeichnisse die gefiltert werden sollen.
	 * @var array
	 */
	private $aDirectories = array();

	/**
	 * Dateien die gefiltert werden sollen.
	 * @var array
	 */
	private $aFiles = array();

	/**
	 * Dateien die in eine Ausnahem im Verzeichnis bilden.
	 * @var array
	 */
	private $aWhitelistedFiles = array();

	/**
	 * Pruefen ob es sich um eine Datei handelt.
	 * @param string $sFile Datei die geprueft werden muss.
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
	 * Ein Verzeichnis filtern.
	 * @param string $sDirectory Zu filterndes Verzeichnis.
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
	 * Eine Datei zum Filter hinzufuegen.
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
	 * Eine Datei zur Whitelist hinzufuegen.
	 * @param string $sFile Dateiname fuer die Ausnahme.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addFileToWhitelist($sFile)
	{
		// Dateifilter geht vor WhiteList.
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
	 * Liste der gefilterten Verzeichnisse zurueck geben.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getFilteredDirectories()
	{
		return $this->aDirectories;
	} // function

	/**
	 * Liste der gefilterten Dateien zurueck geben.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getFilteredFiles()
	{
		return $this->aFiles;
	} // function

	/**
	 * Liste der erlaubten Dateien zurueck geben.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getWhiteListFiles()
	{
		return $this->aWhitelistedFiles;
	} // function
} // class
