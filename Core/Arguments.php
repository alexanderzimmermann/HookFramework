<?php
/**
 * Argumente des Aufrufs behandeln.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Argumente des Aufrufs behandeln.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Arguments
{
	/**
	 * Gueltige Hooks.
	 * @var array
	 */
	private $aHooks = array(
					   'post-commit', 'post-lock', 'post-revprop-change',
					   'post-unlock', 'pre-commit', 'pre-lock',
					   'pre-revprop-change', 'pre-unlock', 'start-commit'
					  );

	/**
	 * Verfuegbare SVN Actions je Typ.
	 * @var array
	 */
	private $aActions = array(
						 'start' => array(
									 'commit' => array(
												  'repos', 'user'
												 )
									),
						 'pre'   => array(
									 'commit'         => array(
														  'repos', 'txn'
														 ),
									 'lock'           => array(
														  'repos', 'user',
														  'file'
														 ),
									 'revprop-change' => array(
														  'repos', 'rev',
														  'user', 'propname',
														  'action'
														 ),

									 'unlock'         => array(
														  'repos', 'user'
														 )
									),

						 'post'  => array(
									 'commit'         => array(
														  'repos', 'rev'
														 ),
									 'lock'           => array(
														  'repos', 'user'
														 ),
									 'revprop-change' => array(
														  'repos', 'rev',
														  'user', 'propname',
														  'action'
														 ),

									 'unlock'         => array(
														  'repos', 'user'
														 )
									),
						);

	/**
	 * Argumente aus dem Aufruf des Hooks.
	 * @var array
	 */
	private $aArguments;

	/**
	 * Anzahl der Argumente.
	 * @var integer
	 */
	private $iArguments;

	/**
	 * Haupttyp fuer den Hook (start, pre, post).
	 * @var string
	 */
	private $sMainType;

	/**
	 * Subtyp fuer den Hook (commit, lock).
	 * @var string
	 */
	private $sSubType;

	/**
	 * Argumente in Ordnung.
	 * @var boolean
	 */
	private $bArgumentsOk;

	/**
	 * Repository Pfad.
	 * @var string
	 */
	private $sRepository;

	/**
	 * Repository Name.
	 * @var string
	 */
	private $sRepositoryName;

	/**
	 * Benutzer.
	 * @var string
	 */
	private $sUser;

	/**
	 * Transaktionsnummer.
	 * @var string
	 */
	private $sTxn;

	/**
	 * Revisionsnummer.
	 * @var integer
	 */
	private $iRev;

	/**
	 * Datei bei Lock.
	 * @var string
	 */
	private $sFile;

	/**
	 * Property Name.
	 * @var string
	 */
	private $sPropertyName;

	/**
	 * Aktion fuer Property.
	 * @var string
	 */
	private $sAction;

	/**
	 * Interner Fehlertext.
	 * @var string
	 */
	private $sError;

	/**
	 * Constructor.
	 * @param array $aArguments Kommandozeilen Argumente.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(array $aArguments)
	{
		// Erstes Argument (Pfad Datei selber) loeschen.
		array_shift($aArguments);

		$this->bArgumentsOk = false;
		$this->aArguments   = $aArguments;
		$this->iArguments   = count($aArguments);
	} // function

	/**
	 * Argumente Ok?.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function argumentsOk()
	{
		$this->checkArguments();

		return $this->bArgumentsOk;
	} // function

	/**
	 * Main Hook Typ zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getMainHook()
	{
		return $this->sMainType . '-' . $this->sSubType;
	} // function

	/**
	 * Haupttyp zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getMainType()
	{
		return $this->sMainType;
	} // function

	/**
	 * Subtyp zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getSubType()
	{
		return $this->sSubType;
	} // function

	/**
	 * Repository Pfad zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getRepository()
	{
		return $this->sRepository;
	} // function

	/**
	 * Return the repository name.
	 * @return string
	 * @author Alexander Zimmermann <alexander.zimmermann@twt.de>
	 */
	public function getRepositoryName()
	{
		return $this->sRepositoryName;
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
	 * Transaktionsnummer zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getTransaction()
	{
		return $this->sTxn;
	} // function

	/**
	 * Versionsnummer zurueckgeben.
	 * @return integer
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getRevision()
	{
		return $this->iRev;
	} // function

	/**
	 * Datei bei Lock zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getFile()
	{
		return $this->sFile;
	} // function

	/**
	 * Property Name zurueckgeben der bei Revprop-Change verlangt wird.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getPropertyName()
	{
		return $this->sPropertyName;
	} // function

	/**
	 * Aktion fuer Property Name zurueckgeben das bei Revprop-Change vorkommt.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getAction()
	{
		return $this->sAction;
	} // function

	/**
	 * Sub Aktonen der Hauptaktion zurueck geben.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getSubActions()
	{
		return array_keys($this->aActions[$this->sMainType]);
	} // function

	/**
	 * Pruefen der Argumente.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkArguments()
	{
		if ($this->checkMainHook() === true)
		{
			if ($this->checkArgumentCount() === true)
			{
				if ($this->checkArgumentTypes() === true)
				{
					$this->bArgumentsOk = true;
					return;
				} // if
			} // if
		} // if

		$this->bArgumentsOk = false;
	} // function

	/**
	 * Pruefen ob der Initial Hook korrekt ist.
	 *
	 * Das letzte Element sollte einen korrekten Wert enthalten.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkMainHook()
	{
		if (true === empty($this->aArguments))
		{
			return false;
		} // if

		$sMain = $this->aArguments[($this->iArguments - 1)];

		if (in_array($sMain, $this->aHooks) === false)
		{
			$this->sError .= 'MainHook ';
			return false;
		} // if

		$aHook           = explode('-', $sMain, 2);
		$this->sMainType = $aHook[0];
		$this->sSubType  = $aHook[1];

		// MainHook Argument entfernen.
		array_pop($this->aArguments);
		$this->iArguments--;

		return true;
	} // function

	/**
	 * Anzahl der Argumente vergleichen.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkArgumentCount()
	{
		$aTypes = $this->aActions[$this->sMainType][$this->sSubType];

		if ($this->iArguments === count($aTypes))
		{
			return true;
		} // if

		$this->sError .= 'Argument Count ';
		return false;
	} // function

	/**
	 * Pruefen der Argumententypen.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkArgumentTypes()
	{
		$iErrors = 0;
		$aTypes  = $this->aActions[$this->sMainType][$this->sSubType];

		foreach ($aTypes as $iIndex => $sType)
		{
			$bResult   = false;
			$sArgument = $this->aArguments[$iIndex];

			$bResult = $this->checkType($sType, $sArgument);

			if ($bResult === false)
			{
				$iErrors++;
			} // if
		} // foreach

		if ($iErrors === 0)
		{
			return true;
		} // if

		$this->sError .= 'Argument Types ';
		return false;
	} // function

	/**
	 * Nach Typ die Pruefroutine aufrufen.
	 * @param string $sType     Typ des Parameters.
	 * @param string $sArgument Wert des Parameters.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkType($sType, $sArgument)
	{
		switch ($sType)
		{
			case 'repos':
				$bResult = $this->checkRepository($sArgument);
			break;

			case 'txn':
				$bResult = $this->checkTransaction($sArgument);
			break;

			case 'user':
				$bResult = $this->checkUser($sArgument);
			break;

			case 'rev':
				$bResult = $this->checkRevision($sArgument);
			break;

			case 'file':
				$bResult = $this->checkFile($sArgument);
			break;

			case 'propname':
				$bResult = $this->checkPropname($sArgument);
			break;

			case 'action':
				$bResult = $this->checkAction($sArgument);
			break;

			default:
				// Da ist etwas falsch.
				$bResult = false;
			break;
		} // switch

		return $bResult;
	} // function

	/**
	 * Pruefen Pfad des Repository ob existiert.
	 *
	 * Pruefen ob dort eine Datei format existiert.
	 * @param string $sRepository Repositorypfad.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkRepository($sRepository)
	{
		if (file_exists($sRepository . '/format') === true)
		{
			$this->sRepository     = $sRepository;
			$this->sRepositoryName = basename($sRepository);
			return true;
		} // if

		$this->sError .= 'Repository ';
		return false;
	} // function

	/**
	 * Pruefen Benutzer.
	 * @param string $sUser Benutzername.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkUser($sUser)
	{
		if (preg_match('/[a-z0-9]+/i', $sUser) > 0)
		{
			$this->sUser = $sUser;
			return true;
		} // if

		$this->sError .= 'User ';
		return false;
	} // function

	/**
	 * Pruefen der Transaktion Nummer (z.B. 501-a, 501-11).
	 * @param string $sTransaction Transaktionsnummer.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkTransaction($sTransaction)
	{
		if (preg_match('/^[0-9]+\-([a-z0-9]{1,2}|[0-9]+)$/', $sTransaction) > 0)
		{
			$this->sTxn = $sTransaction;
			return true;
		} // if

		$this->sError .= 'Transaction ';
		return false;
	} // function

	/**
	 * Pruefen der Revisionsnummer (z.B. 501).
	 * @param string $sRevision Revisionsnummer.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkRevision($sRevision)
	{
		if (preg_match('/[0-9]+/', $sRevision) > 0)
		{
			$this->iRev = (int) $sRevision;
			return true;
		} // if

		$this->sError .= 'Revision ';
		return false;
	} // function

	/**
	 * Pruefen ob Datei korrekt ist.
	 * @param string $sFile Dateiname.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkFile($sFile)
	{
		$this->sFile = $sFile;
		return true;
	} // function

	/**
	 * Property Name pruefen.
	 * @param string $sPropertyName Eigenschaftsname.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkPropname($sPropertyName)
	{
		$this->sPropertyName = $sPropertyName;
		return true;
	} // function

	/**
	 * Propset Action pruefen.
	 * @param string $sAction Aktion (A, M, D).
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkAction($sAction)
	{
		if (preg_match('/[AMD]+/i', $sAction) > 0)
		{
			$this->sAction = $sAction;
			return true;
		} // if

		$this->sError .= 'Action false, not (A, M, D)';
		return false;
	} // function
} // class
