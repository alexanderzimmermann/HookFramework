<?php
/**
 * Klasse fuer die Objekte die commited wurden.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once 'CommitBase.php';

/**
 * Klasse fuer die Objekte die commited wurden.
 *
 * Der Inhalt der Datei steht nicht in der Klasse, aus Performancegruenden wird
 * dieser nur geholt wenn die Daten benoetigt werden. Das wird durch die
 * Listener bestimmt.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class CommitObject extends CommitBase
{
	/**
	 * Objektaktion (A, U, D).
	 * @var string
	 */
	private $sAction;

	/**
	 * Verzeichnis oder Datei.
	 * @var boolean
	 */
	private $bIsDir;

	/**
	 * Objektpfad.
	 * @var string
	 */
	private $sObjectPath;

	/**
	 * Commit Info Objekt.
	 * @var CommitInfo
	 */
	private $oCommitInfo;

	/**
	 * Temporärer Pfad zum Objekt.
	 * @var string
	 */
	private $sTmpObjectPath;

	/**
	 * Actual properties of the object.
	 * @var array
	 */
	private $aProperties;

	/**
	 * Changed properties.
	 * @var array
	 */
	private $aChangedProperties;

	/**
	 * Changed lines.
	 * @var Diff_Lines
	 */
	private $oChangedLines;

	/**
	 * Constructor.
	 * @param array $aParams Params for the object.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(array $aParams)
	{
		parent::__construct($aParams['txn'], $aParams['rev']);

		$this->sAction            = $aParams['action'];
		$this->bIsDir             = $aParams['isdir'];
		$this->sObjectPath        = $aParams['item'];
		$this->oCommitInfo        = $aParams['info'];
		$this->aChangedProperties = $aParams['props'];
		$this->oChangedLines      = $aParams['lines'];

		// Verzeichnispfad umwandeln.
		if ($aParams['isdir'] === false)
		{
			$sPath = str_replace('/', '_', $aParams['item']);
			$sPath = '/tmp/' . $aParams['txn'] . '-' . $sPath;

			$this->sTmpObjectPath = $sPath;
		} // if
	} // function

	/**
	 * Aktion zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getAction()
	{
		return $this->sAction;
	} // function

	/**
	 * Ist das Objekt ein Verzeichnis.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getIsDir()
	{
		return $this->bIsDir;
	} // function

	/**
	 * Pfad zu der Datei zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getObjectPath()
	{
		return $this->sObjectPath;
	} // function

	/**
	 * Das Commit Info Objekt zurueck geben.
	 * @return CommitInfo
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getCommitInfo()
	{
		return $this->oCommitInfo;
	} // function

	/**
	 * Temporaeren Pfad zur Datei zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getTmpObjectPath()
	{
		return $this->sTmpObjectPath;
	} // function

	/**
	 * Return the actual properties.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getActualProperties()
	{
		return $this->aProperties;
	} // function

	/**
	 * Return the changed properties.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getChangedProperties()
	{
		return $this->aChangedProperties;
	} // function

	/**
	 * Return the changed lines.
	 * @return Diff_Lines
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getChangedLines()
	{
		return $this->oChangedLines;
	} // function
} // class
