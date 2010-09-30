<?php
/**
 * Klasse fuer die Informationen die commited wurden.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once 'CommitBase.php';

/**
 * Klasse fuer die Informationen die commited wurden.
 * @category   Core
 * @package    Commit
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class CommitInfo extends CommitBase
{
	/**
	 * Benutzername.
	 * @var string
	 */
	private $sUser;

	/**
	 * Datum Zeit.
	 * @var string
	 */
	private $sDateTime;

	/**
	 * Textbemerkung des Commits.
	 * @var string
	 */
	private $sMessage;

	/**
	 * Liste der Objekte.
	 * @var array
	 */
	private $aObjects;

	/**
	 * Constructor.
	 * @param string $sTxn      Transaction, wenn Pre.
	 * @param string $iRev      Revision (falls vorh.).
	 * @param string $sUser     Benutzername des Commits.
	 * @param string $sDateTime Datum Uhrzeit des Commits.
	 * @param string $sMessage  Anmerkung zum Commit.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct($sTxn, $iRev, $sUser, $sDateTime, $sMessage)
	{
		parent::__construct($sTxn, $iRev);
		$this->sUser     = $sUser;
		$this->sDateTime = $sDateTime;
		$this->sMessage  = $sMessage;
		$this->aObjects  = array();
	} // function

	/**
	 * Setzen der Liste der Objekte des Commits.
	 * @param array $aObjects Liste der Objekte.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function setObjects(array $aObjects)
	{
		if (empty($this->aObjects) === true)
		{
			$this->aObjects = $aObjects;
		} // if
	} // function

	/**
	 * Benutzer zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getUser()
	{
		return $this->sUser;
	} // function

	/**
	 * Datum Zeit zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getDateTime()
	{
		return $this->sDateTime;
	} // function

	/**
	 * Textbemerkung zum Commit zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getMessage()
	{
		return $this->sMessage;
	} // function

	/**
	 * Liste der Objekte zurueck geben.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getObjects()
	{
		return $this->aObjects;
	} // function
} // class
