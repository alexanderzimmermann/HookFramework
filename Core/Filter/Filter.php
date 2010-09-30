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
	 * Gefilterte Dateien.
	 * @var array
	 */
	private $aFiles;

	/**
	 * Ausnahmen fuer Dateien.
	 * @var array
	 */
	private $aWhiteFiles;

	/**
	 * Konstruktor.
	 * @param array $aObjects Commit Objekte.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(array $aObjects)
	{
		$this->aObjects    = $aObjects;
		$this->aNewObjects = array();
	} // function

	/**
	 * Die Objekte mit dem Filter des Listener abgleichen und filtern.
	 * @param ObjectFilter $oObjectFilter Objekt Filter.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getFilteredFiles(ObjectFilter $oObjectFilter)
	{
		$this->aDirectories = $oObjectFilter->getFilteredDirectories();
		$this->aFiles       = $oObjectFilter->getFilteredFiles();
		$this->aWhiteFiles  = $oObjectFilter->getWhiteListFiles();

		// If all is emtpy then return all.
		if ((empty($this->aDirectories) === true) &&
			(empty($this->aFiles) === true) &&
			(empty($this->aWhiteFiles) === true))
		{
			return $this->aObjects;
		} // if

		// Pfade extrahieren.
		$this->getPaths();

		// Filter abarbeiten.
		$this->handleWhiteList();
		$this->handleDirectories();
		$this->handleFiles();

		// White List + die gefilterten Objekte.
		$this->aNewObjects = array_merge($this->aNewObjects, $this->aObjects);
		$this->aNewObjects = array_values($this->aNewObjects);

		return $this->aNewObjects;
	} // function

	/**
	 * Aus den Objekte die Pfade zum Filtern ziehen.
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
	 * Alle White List Objekte in das neue Array uebertragen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function handleWhiteList()
	{
		if (empty($this->aWhiteFiles) === true)
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
	 * Delete every file that lies within a "forbidden" directory.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function handleDirectories()
	{
		if (empty($this->aDirectories) === true)
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

				// Wenn ein Unterschied besteht, dann liegt die Datei im Dir.
				if ($sResult !== $sPath)
				{
					unset($this->aPaths[$iIndex]);
					unset($this->aObjects[$iIndex]);
				} // if
			} // foreach
		} // for
	} // function

	/**
	 * Die Einzeldateien loeschen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function handleFiles()
	{
		if (empty($this->aFiles) === true)
		{
			return;
		} // if

		foreach ($this->aPaths as $iIndex => $sPath)
		{
			if (in_array($sPath, $this->aFiles) === true)
			{
				unset($this->aPaths[$iIndex]);
				unset($this->aObjects[$iIndex]);
			} // if
		} // foreach
	} // function
} // class
