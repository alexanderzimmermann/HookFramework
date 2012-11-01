<?php
/**
 * Data in transaction.
 * @category   Core
 * @package    Parser
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Commit;

use Hook\Core\Arguments;
use Hook\Core\Svn;
use Hook\Commit\Data;
use Hook\Commit\Parser\Parser as DiffParser;

/**
 * Data in the transaction.
 * @category   Core
 * @package    Parser
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Parser
{
	/**
	 * Main Hook.
	 * @var Arguments
	 */
	private $oArguments;

	/**
	 * Svn object.
	 * @var Svn
	 */
	private $oSvn;

	/**
	 * Data Object.
	 * @var Data
	 */
	private $oData;

	/**
	 * Parsed changed items.
	 * @var array
	 */
	private $aChangedItems;

	/**
	 * Parse changed data.
	 * @var array
	 */
	private $aChangedData;

	/**
	 * Constructor.
	 * @param Arguments $oArguments Arguments object.
	 * @param Svn       $oSvn       Subversion object.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(Arguments $oArguments, Svn $oSvn)
	{
		$this->aChangedItems = array();
		$this->aChangedData  = array();

		$this->oArguments = $oArguments;
		$this->oSvn       = $oSvn;

		$sTxn        = $oArguments->getTransaction();
		$iRev        = $oArguments->getRevision();
		$this->oData = new Data();

		if ($this->oArguments->getMainType() === 'start')
		{
			$aInfos['txn']      = $sTxn;
			$aInfos['rev']      = $iRev;
			$aInfos['user']     = $oArguments->getUser();
			$aInfos['datetime'] = date('Y-m-d H:i:s', time());
			$aInfos['message']  = 'No Message in Start Hook';
			$this->oData->createCommitInfo($aInfos);

			return;
		} // if

		$this->getCommitInfo();

		$aDiffLines = $this->oSvn->getCommitDiff();
		$aChanged   = $this->oSvn->getCommitChanged();

		$this->parseItems($aChanged);

		$oParser = new DiffParser($this->aChangedItems, $aDiffLines);
		$oParser->parse();

		$this->createCommitObjects($oParser->getProperties(), $oParser->getLines());
	} // function

	/**
	 * Return commit data object.
	 * @return Data
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getCommitDataObject()
	{
		return $this->oData;
	} // function

	/**
	 * Parse info from commit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getCommitInfo()
	{
		$this->parseCommitInfo($this->oSvn->getCommitInfo());
	} // function

	/**
	 * Parse array with the changed items.
	 *
	 * Determine directory or file.
	 * Determine if item is added, updated or deleted.
	 * <ul>
	 * <li>A</li>
	 * <li>U</li>
	 * <li>D</li>
	 * </ul>
	 * @param array $aData Items of the commit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function parseItems(array $aData)
	{
		$iMax = count($aData);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			/**
				The following format could appear:
				U   {File}
				_U  {File}
				UU  {File}
			*/

			$sObject       = $aData[$iFor];
			$sActionInfo   = substr($sObject, 0, 4);
			$sObjectAction = trim(str_replace('_', '', $sActionInfo));
			$sObjectAction = strtoupper(substr($sObjectAction, 0, 1));
			$sObject       = str_replace($sActionInfo, '', $sObject);

			// Directory or file.
			// If it is ok, we detect a directory on that / at last postion.
			$bIsDir = false;
			$sItem  = $sObject;
			if (substr($sObject, -1) === '/')
			{
				$bIsDir = true;
				$sItem  = substr($sObject, 0, -1);
			} // if

			$aTmp = array(
					 'item'   => $sObject,
					 'action' => $sObjectAction,
					 'isdir'  => $bIsDir
					);

			$this->aChangedItems[] = $sItem;
			$this->aChangedData[]  = $aTmp;
		} // for
	} // function

	/**
	 * Creating the data for the listener.
	 * @param array $aProperties Properties of each item, if available.
	 * @param array $aLines      Changed lines of each item, if available.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function createCommitObjects(array $aProperties, array $aLines)
	{
		// Values for all items.
		$sTxn = $this->oArguments->getTransaction();
		$iRev = $this->oArguments->getRevision();

		$aObjects = array();
		foreach ($this->aChangedData as $iFor => $aData)
		{
			$aParams = array(
						'txn'    => $sTxn,
						'rev'    => $iRev,
						'action' => $aData['action'],
						'item'   => $aData['item'],
						'isdir'  => $aData['isdir'],
						'props'  => array(),
						'lines'  => null
					   );

			if (true === isset($aProperties[$iFor]))
			{
				$aParams['props'] = $aProperties[$iFor];
			} // if

			if (true === isset($aLines[$iFor]))
			{
				$aParams['lines'] = $aLines[$iFor];
			} // if

			$aObjects[] = $this->oData->createObject($aParams);
		} // foreach

		$this->oData->getCommitInfo()->setObjects($aObjects);
	} // function

	/**
	 * Parse info from the commit.
	 * @param array $aData Commit Data info.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function parseCommitInfo(array $aData)
	{
		// Vorbelegen.
		$aInfos                  = array();
		$aInfos['txn']           = $this->oArguments->getTransaction();
		$aInfos['rev']           = $this->oArguments->getRevision();
		$aInfos['user']          = '';
		$aInfos['datetime']      = '';
		$aInfos['messagelength'] = 0;
		$aInfos['message']       = '';

		// Diese Elemente in dieser Reihenfolge.
		$aPropertys = array(
					   'user', 'datetime', 'messagelength', 'message'
					  );

		$iMax = count($aData);

		// Discard empty elements. Count could also be 0.
		if ($iMax > 4)
		{
			$iMax = 4;
		} // if

		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$sData = $aData[$iFor];
			if ($aPropertys[$iFor] === 'message')
			{
				$aData[$iFor] = $this->parseMessage($sData);
			} // if

			$aInfos[$aPropertys[$iFor]] = trim($aData[$iFor]);
		} // for

		$this->oData->createCommitInfo($aInfos);
	} // function

	/**
	 * Parse message.
	 * @param string $sMessage Commit Text.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function parseMessage($sMessage)
	{
		$aMatches = array();

		// Replace special signs in Format \\123.
		preg_match_all('/\?\\\\\\\\([0-9]+)/', $sMessage, $aMatches);

		$iMax = count($aMatches[0]);
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$sChr     = $aMatches[0][$iFor];
			$iChr     = (int) $aMatches[1][$iFor];
			$sMessage = str_replace($sChr, chr($iChr), $sMessage);
		} // for

		return $sMessage;
	} // function
} // class
