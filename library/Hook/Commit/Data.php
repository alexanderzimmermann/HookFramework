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

use Hook\Commit\Data\Info;
use Hook\Commit\Data\Object;
use Hook\Filter\Filter;
use Hook\Listener\AbstractObject;

/**
 * Data in the transaction.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Data
{
	/**
	 * Commit Information.
	 * @var Info
	 */
	private $oInfo;

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
	 * @return Object
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function createObject(array $aParams)
	{
		$aParams['info'] = $this->oInfo;
		$oObject         = new Object($aParams);

		if ($aParams['isdir'] === true)
		{
			$this->aObjects[$aParams['action']]['DIRS'][] = $oObject;
		}
		else
		{
			// Determine Files after extensions.
			$sExt = $this->determineFileExtension($aParams['item']);

			$this->aObjects[$aParams['action']]['FILES']['ALL'][] = $oObject;
			$this->aObjects[$aParams['action']]['FILES'][$sExt][] = $oObject;
		} // if

		return $oObject;
	} // function

	/**
	 * Return the commit info object.
	 * @return Info
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getInfo()
	{
		return $this->oInfo;
	} // function

	/**
	 * Return the objects depending on the action.
	 * @param AbstractObject $oListener Listener Objekt.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getObjects(AbstractObject $oListener)
	{
		$aRegister    = $oListener->register();
		$aActionTypes = $aRegister['fileaction'];
		$aExtensions  = $aRegister['extensions'];
		$bWithDirs    = (bool) $aRegister['withdirs'];

		$aObjects = array();

		// If both arrays are empty, then there is no data, maybe just property.
		if ((empty($aActionTypes) === true) && (empty($aExtensions) === true))
		{
			return $aObjects;
		} // if

		// If one of the arrays is empty, then the other not set to all.
		if ((empty($aActionTypes) === true) && (empty($aExtensions) === false))
		{
			$aActionTypes = array(
							 'A', 'U', 'D'
							);
		} // if

		// If extensions is empty, then we want all files.
		if ((empty($aActionTypes) === false) && (empty($aExtensions) === true))
		{
			$aExtensions = array('ALL');
		} // if

		// Search for the requested objects.
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

				// Add directories if required.
				if ($bWithDirs === true)
				{
					$aAddDirs = $this->aObjects[$sAction]['DIRS'];
					$aObjects = array_merge($aObjects, $aAddDirs);
				} // if
			} // if
		} // foreach

		// List of files empty? Then return empty.
		if (empty($aObjects) === true)
		{
			return $aObjects;
		} // if

		// Now recognize the filter of the listener.
		$oFilter  = new Filter($aObjects);
		$aObjects = $oFilter->getFilteredFiles($oListener->getObjectFilter());

		return $aObjects;
	} // function

	/**
	 * Determine file extensions.
	 * @param string $sFile File to determine extension for.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function determineFileExtension($sFile)
	{
		// Search first point, starting from end of filename.
		// If no point is found, its a file without extension.
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
	 * Create commit info object.
	 * @param array $aInfos Commit Information.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function createInfo(array $aInfos)
	{
		$this->oInfo = new Info(
									$aInfos['txn'],
									$aInfos['rev'],
									$aInfos['user'],
									$aInfos['datetime'],
									$aInfos['message']
								 );
	} // function
} // class