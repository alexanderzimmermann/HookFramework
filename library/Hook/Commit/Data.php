<?php
/**
 * Data in the transaction.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Commit;

use Hook\Commit\CommitInfo;
use Hook\Commit\CommitObject;
use Hook\Filter\Filter;
use Hook\Listener\ObjectAbstract;

/**
 * Data in the transaction.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Data
{
	/**
	 * Commit Information.
	 * @var CommitInfo
	 */
	private $oCommitInfo;

	/**
	 * All directories and file objects. (Multi dimension Array).
	 * @var array
	 */
	private $aObjects;

	/**
	 * Constructor.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct()
	{
		$this->aObjects['A']['FILES'] = array();
		$this->aObjects['A']['DIRS']  = array();
		$this->aObjects['U']['FILES'] = array();
		$this->aObjects['U']['DIRS']  = array();
		$this->aObjects['D']['FILES'] = array();
		$this->aObjects['D']['DIRS']  = array();
	} // function

	/**
	 * Add an object.
	 * @param array $aParams Params for the commit object.
	 * @return CommitObject
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function createObject(array $aParams)
	{
		$aParams['info'] = $this->oCommitInfo;
		$oCommitObject   = new CommitObject($aParams);
		$sObjectAction   = $aParams['action'];

		if ($aParams['isdir'] === true)
		{
			$this->aObjects[$sObjectAction]['DIRS'][] = $oCommitObject;
		}
		else
		{
			// Determine Files after extensions.
			$sExt = $this->determineFileExtension($aParams['item']);

			$this->aObjects[$sObjectAction]['FILES']['ALL'][] = $oCommitObject;
			$this->aObjects[$sObjectAction]['FILES'][$sExt][] = $oCommitObject;
		} // if

		return $oCommitObject;
	} // function

	/**
	 * Return the commit info object.
	 * @return CommitInfo
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getCommitInfo()
	{
		return $this->oCommitInfo;
	} // function

	/**
	 * Objekte nach Aktion und Extension zurueck geben.
	 * @param ObjectAbstract $oListener Listener Objekt.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getObjects(ObjectAbstract $oListener)
	{
		$aRegister    = $oListener->register();
		$aActionTypes = $aRegister['fileaction'];
		$aExtensions  = $aRegister['extensions'];
		$bWithDirs    = (bool) $aRegister['withdirs'];

		$aObjects = array();

		// Wenn die beiden Arrays leer sind, dann gibts auch keine Daten.
		if ((empty($aActionTypes) === true) && (empty($aExtensions) === true))
		{
			return $aObjects;
		} // if

		// Wenn einer der Arrays leer ist der andere nicht dann auf ALL setzen.
		if ((empty($aActionTypes) === true) && (empty($aExtensions) === false))
		{
			$aActionTypes = array(
							 'A', 'U', 'D'
							);
		} // if

		if ((empty($aActionTypes) === false) && (empty($aExtensions) === true))
		{
			$aExtensions = array('ALL');
		} // if

		// Nach den gewuenschten Objekten suchen.
		foreach ($aActionTypes as $iIndex => $sAction)
		{
			if (isset($this->aObjects[$sAction]) === true)
			{
				foreach ($aExtensions as $iIndex => $sExt)
				{
					if (isset($this->aObjects[$sAction]['FILES'][$sExt]) === true)
					{
						$aAddFiles = $this->aObjects[$sAction]['FILES'][$sExt];
						$aObjects  = array_merge($aObjects, $aAddFiles);
					} // if
				} // foreach

				// Verzeichnisse hinzufuegen falls erforderlich.
				if ($bWithDirs === true)
				{
					$aAddDirs = $this->aObjects[$sAction]['DIRS'];
					$aObjects = array_merge($aObjects, $aAddDirs);
				} // if
			} // if
		} // foreach

		// Liste der Dateien leer? Dann leer zurueck geben.
		if (empty($aObjects) === true)
		{
			return $aObjects;
		} // if

		// Filter des Listener beruecksichtigen.
		$oFilter  = new Filter($aObjects);
		$aObjects = $oFilter->getFilteredFiles($oListener->getObjectFilter());

		return $aObjects;
	} // function

	/**
	 * File Extension ermitteln.
	 * @param string $sFile Datei fuer die die Erweiterung ermittelt wird.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function determineFileExtension($sFile)
	{
		// Ersten Punkt von hinten suchen. Wenn kein Punkt gefunden wird,
		// sollte es eine Datei ohne Erweiterung sein.
		$iPos       = strrpos($sFile, '.');
		$sExtension = '';

		if ($iPos !== false)
		{
			$iPos++;
			$sExtension = substr($sFile, $iPos, (strlen($sFile) - $iPos));
		} // if

		return strtoupper($sExtension);
	} // function

	/**
	 * Commit Info Objekt erstellen.
	 * @param array $aInfos Commit Infos.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function createCommitInfo(array $aInfos)
	{
		$this->oCommitInfo = new CommitInfo(
									$aInfos['txn'],
									$aInfos['rev'],
									$aInfos['user'],
									$aInfos['datetime'],
									$aInfos['message']
								 );
	} // function
} // class