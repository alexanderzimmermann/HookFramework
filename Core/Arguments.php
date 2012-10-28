<?php
/**
 * Class for handling the arguments of a hook call.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Core;

/**
 * Class for handling the arguments of a hook call.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Arguments
{
	/**
	 * Valid hooks (complete name).
	 * @var array
	 */
	private $aHooks = array(
					   'post-commit', 'post-lock', 'post-revprop-change',
					   'post-unlock', 'pre-commit', 'pre-lock',
					   'pre-revprop-change', 'pre-unlock', 'start-commit'
					  );

	/**
	 * Available svn actions for each type.
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
	 * Arguments of hook call.
	 * @var array
	 */
	private $aArguments;

	/**
	 * arguments count.
	 * @var integer
	 */
	private $iArguments;

	/**
	 * main type for hook call (start, pre, post).
	 * @var string
	 */
	private $sMainType;

	/**
	 * Subtype for Hook (commit, lock).
	 * @var string
	 */
	private $sSubType;

	/**
	 * Arguments Ok.
	 * @var boolean
	 */
	private $bArgumentsOk;

	/**
	 * Repository path.
	 * @var string
	 */
	private $sRepository;

	/**
	 * Repository name.
	 * @var string
	 */
	private $sRepositoryName;

	/**
	 * Username of commit.
	 * @var string
	 */
	private $sUser;

	/**
	 * Transaction number.
	 * @var string
	 */
	private $sTxn;

	/**
	 * Revision number.
	 * @var integer
	 */
	private $iRev;

	/**
	 * file of lock.
	 * @var string
	 */
	private $sFile;

	/**
	 * Property Name.
	 * @var string
	 */
	private $sPropertyName;

	/**
	 * Action for property.
	 * @var string
	 */
	private $sAction;

	/**
	 * Internal error text.
	 * @var string
	 */
	private $sError;

	/**
	 * Constructor.
	 * @param array $aArguments Command line arguments.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(array $aArguments)
	{
		// Delete first element (path file).
		array_shift($aArguments);

		$this->bArgumentsOk = false;
		$this->aArguments   = $aArguments;
		$this->iArguments   = count($aArguments);
	} // function

	/**
	 * Arguments Ok.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function argumentsOk()
	{
		$this->checkArguments();

		return $this->bArgumentsOk;
	} // function

	/**
	 * Return complete hook type.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getMainHook()
	{
		return $this->sMainType . '-' . $this->sSubType;
	} // function

	/**
	 * Return only main type string.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getMainType()
	{
		return $this->sMainType;
	} // function

	/**
	 * Return subtype.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getSubType()
	{
		return $this->sSubType;
	} // function

	/**
	 * Return repository path.
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
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getRepositoryName()
	{
		return $this->sRepositoryName;
	} // function

	/**
	 * Return user.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getUser()
	{
		return $this->sUser;
	} // function

	/**
	 * Return Transaction number.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getTransaction()
	{
		return $this->sTxn;
	} // function

	/**
	 * Return version number.
	 * @return integer
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getRevision()
	{
		return $this->iRev;
	} // function

	/**
	 * Return the file on lock call.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getFile()
	{
		return $this->sFile;
	} // function

	/**
	 * Return property name that is required on a Revprop-Change hook call.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getPropertyName()
	{
		return $this->sPropertyName;
	} // function

	/**
	 * Returns action name.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getAction()
	{
		return $this->sAction;
	} // function

	/**
	 * Returns all available sub actions from the main action.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getSubActions()
	{
		return array_keys($this->aActions[$this->sMainType]);
	} // function

	/**
	 * Returns error text.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getError()
	{
		return $this->sError;
	} // function

	/**
	 * Check the arguments.
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
	 * Check if the initial hook call is correct.
	 *
	 * The last element of the parameters should contain the correct value.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkMainHook()
	{
		if (true === empty($this->aArguments))
		{
			$this->sError = 'Empty Arguments';
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
	 * Compare count of arguments.
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
	 * Check the arguments.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkArgumentTypes()
	{
		$iErrors = 0;
		$aTypes  = $this->aActions[$this->sMainType][$this->sSubType];

		foreach ($aTypes as $iIndex => $sType)
		{
			$sArgument = $this->aArguments[$iIndex];
			$bResult   = $this->checkType($sType, $sArgument);

			if (false === $bResult)
			{
				$iErrors++;
			} // if
		} // foreach

		if (0 === $iErrors)
		{
			return true;
		} // if

		return false;
	} // function

	/**
	 * Call check routine after the type value.
	 * @param string $sType     Type parameter.
	 * @param string $sArgument Value of parameter.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkType($sType, $sArgument)
	{
		$sFunction = 'check' . ucfirst($sType);

		if (true === method_exists($this, $sFunction))
		{
			return $this->$sFunction($sArgument);
		} // if

		$bResult = false;
		$this->sError .= 'Check Type Error for ' . $sType;

		return $bResult;
	} // function

	/**
	 * Check if the repository exists.
	 *
	 * Just a simple test if the file format exists.
	 * @param string $sRepository Repository path.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkRepos($sRepository)
	{
		if (true === file_exists($sRepository . '/format'))
		{
			$this->sRepository     = $sRepository;
			$this->sRepositoryName = basename($sRepository);
			return true;
		} // if

		$this->sError .= 'Repository ' . $sRepository . ' does not exists.';

		return false;
	} // function

	/**
	 * Checks the user.
	 * @param string $sUser Username.
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
	 * Check the transaction number (z.B. 501-a, 501-11).
	 * @param string $sTransaction Transaction number.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkTxn($sTransaction)
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
	 * Checks the revision number (i.e. 501).
	 * @param string $sRevision Revision number.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkRev($sRevision)
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
	 * Check if file is correct.
	 * @param string $sFile Filename.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkFile($sFile)
	{
		$this->sFile = $sFile;
		return true;
	} // function

	/**
	 * Check property name.
	 * @param string $sPropertyName Property name.
	 * @return boolean
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkPropname($sPropertyName)
	{
		$this->sPropertyName = $sPropertyName;
		return true;
	} // function

	/**
	 * Check propset action.
	 * @param string $sAction Action (A, M, D).
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