<?php
/**
 * Basisklasse fuer die Commit Daten.
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

namespace Core\Commit;

/**
 * Basisklasse fuer die Commit Daten.
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
class CommitBase
{
	/**
	 * Transaktion.
	 * @var string
	 */
	protected $sTxn;

	/**
	 * Revision.
	 * @var integer
	 */
	protected $iRev;

	/**
	 * Fehlerzeilen.
	 * @var array
	 */
	protected $aErrorLines;

	/**
	 * Constructor.
	 * @param string  $sTxn Transaktion Nummer (666-1).
	 * @param integer $iRev Revisionsnummer (666).
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct($sTxn, $iRev)
	{
		$this->sTxn = $sTxn;
		$this->iRev = $iRev;

		$this->aErrorLines = array();
	} // function

	/**
	 * Transaktionsnummer zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getTransaction()
	{
		return $this->sTxn;
	} // function

	/**
	 * Revisionsnummer zurueck geben.
	 * @return integer
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getRevision()
	{
		return $this->iRev;
	} // function

	/**
	 * Fehler hinzufuegen.
	 * @param string $sError Fehlermeldung.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addError($sError)
	{
		$this->aErrorLines[] = $sError;
	} // function

	/**
	 * Fehlerzeilen hinzufuegen.
	 * @param array $aErrorLines Fehlerzeilen.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function addErrorLines(array $aErrorLines)
	{
		$this->aErrorLines = array_merge($this->aErrorLines, $aErrorLines);
	} // function

	/**
	 * Fehlerzeilen zurueck geben.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getErrorLines()
	{
		$aErrorLines       = $this->aErrorLines;
		$this->aErrorLines = array();

		return $aErrorLines;
	} // function
} // class
