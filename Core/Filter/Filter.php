<?php
/**
 * Filterklasse um die Objekte zu filtern.
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
 * Filterklasse um die Objekte zu filtern.
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
class Filter
{
	/**
	 * Liste der angeforderten Objekte.
	 * @var array
	 */
	private $aObjects;

	/**
	 * Neues Liste der angeforderten Objekte.
	 * @var array
	 */
	private $aNewObjects;

	/**
	 * Pfade der Dateien.
	 * @var array
	 */
	private $aPaths;

	/**
	 * Gefilterte Verzeichnisse.
	 * @var array
	 */
	private $aDirectories;

	/**
	 * Files to filter.
	 * @var array
	 */
	private $aFiles;

	/**
	 * Directories that are allowed in forbidden areas.
	 * @var array
	 */
	private $aWhiteDirs;

	/**
	 * Files that are allowed in forbidden areas.
	 * @var array
	 */
	private $aWhiteFiles;

	/**
	 * Constructor.
	 * @param array $aObjects Commit Objects.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(array $aObjects)
	{
		$this->aObjects    = $aObjects;
		$this->aNewObjects = array();
	} // function

	/**
	 * Compare commit objects paths with the filter of the listener.
	 * @param ObjectFilter $oObjectFilter Object filter.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getFilteredFiles(ObjectFilter $oObjectFilter)
	{
		$this->aDirectories = $oObjectFilter->getFilteredDirectories();
		$this->aFiles       = $oObjectFilter->getFilteredFiles();
		$this->aWhiteDirs   = $oObjectFilter->getWhiteListDirectories();
		$this->aWhiteFiles  = $oObjectFilter->getWhiteListFiles();

		// If all is emtpy then return all.
		if ((true === empty($this->aDirectories)) &&
			(true === empty($this->aFiles)) &&
			(true === empty($this->aWhiteFiles)) &&
			(true === empty($this->aWhiteDirs)))
		{
			return $this->aObjects;
		} // if

		// Extract paths.
		$this->getPaths();

		// Process the filters.
		$this->handleWhiteListFiles();
		$this->handleWhiteListDirectories();
		$this->handleDirectories();
		$this->handleFiles();

		// White List + the filtered objects.
		$this->aNewObjects = array_merge($this->aNewObjects, $this->aObjects);
		$this->aNewObjects = array_values($this->aNewObjects);

		return $this->aNewObjects;
	} // function

	/**
	 * Take the paths from the objects to filter them.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getPaths()
	{
		// Pfade aus dem Array extrahieren fuer die Filter.
		$iMax = count($this->aObjects);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$this->aPaths[] = $this->aObjects[$iFor]->getObjectPath();
		} // for
	} // function

	/**
	 * Copy all white list objects into the new array.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function handleWhiteListFiles()
	{
		if (true === empty($this->aWhiteFiles))
		{
			return;
		} // if

		foreach ($this->aPaths as $iIndex => $sPath)
		{
			if (in_array($sPath, $this->aWhiteFiles) === true)
			{
				$this->aNewObjects[] = $this->aObjects[$iIndex];
			} // if
		} // foreach
	} // function

	/**
	 * Copy itmes in a white listed directory into the new array.
	 * @return void
	 * @author Alexander Zimmermann <alexander.zimmermann@twt.de>
	 */
	private function handleWhiteListDirectories()
	{
		if (true === empty($this->aWhiteDirs))
		{
			return;
		} // if

		$iMax = count($this->aWhiteDirs);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$sDir = $this->aWhiteDirs[$iFor];
			$sDir = str_replace('/', '\/', $sDir);

			foreach ($this->aPaths as $iIndex => $sPath)
			{
				$sResult = preg_replace('/^' . $sDir . '/', '', $sPath);

				// If there is a difference, the file is in the directoy.
				if ($sResult !== $sPath)
				{
					$this->aNewObjects[] = $this->aObjects[$iIndex];
				} // if
			} // foreach
		} // for
	} // function

	/**
	 * Delete every file that lies within a "forbidden" directory.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function handleDirectories()
	{
		if (true === empty($this->aDirectories))
		{
			return;
		} // if

		$iMax = count($this->aDirectories);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$sDir = $this->aDirectories[$iFor];
			$sDir = str_replace('/', '\/', $sDir);

			foreach ($this->aPaths as $iIndex => $sPath)
			{
				$sResult = preg_replace('/^' . $sDir . '/', '', $sPath);

				// If there is a difference, the file is in the directoy.
				if ($sResult !== $sPath)
				{
					unset($this->aPaths[$iIndex]);
					unset($this->aObjects[$iIndex]);
				} // if
			} // foreach
		} // for
	} // function

	/**
	 * Delete single files.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function handleFiles()
	{
		if (true === empty($this->aFiles))
		{
			return;
		} // if

		foreach ($this->aPaths as $iIndex => $sPath)
		{
			if (true === in_array($sPath, $this->aFiles))
			{
				unset($this->aPaths[$iIndex]);
				unset($this->aObjects[$iIndex]);
			} // if
		} // foreach
	} // function
} // class
