<?php
/**
 * SVN- Klasse zum ausfuehren der SVN-Befehle.
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
 * SVN- Klasse zum ausfuehren der SVN-Befehle.
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
class Svn
{
	/**
	 * Pfad zu den SVN- Bin's.
	 * @var string
	 */
	private $sSvnPath = '/usr/bin/';

	/**
	 * SVN Kommand (inkl. Pfad).
	 * @var stringt
	 */
	private $sSvnCmd;

	/**
	 * Repository.
	 * @var string
	 */
	private $sRepos;

	/**
	 * Svn Param (Transaktion [-t TXN] oder Revision [-r REV]).
	 * @var string
	 */
	private $sSvnLookParam;

	/**
	 * Konstruktor.
	 * @param string $sSvnPath Pfad zur ausfuehrbaren Datei svnlook.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct($sSvnPath)
	{
		$this->sSvnPath = $sSvnPath;
	} // function

	/**
	 * Initialisieren.
	 * @param Arguments $oArguments Kommandozeilenargumente.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function init(Arguments $oArguments)
	{
		$this->sRepos  = $oArguments->getRepository();
		$this->sSvnCmd = $this->sSvnPath . 'svnlook';

		if ($oArguments->getMainType() === 'pre')
		{
			$this->sSvnLookParam  = ' -t ';
			$this->sSvnLookParam .= $oArguments->getTransaction();
		} // if

		if ($oArguments->getMainType() === 'post')
		{
			$this->sSvnLookParam  = ' -r ';
			$this->sSvnLookParam .= $oArguments->getRevision();
		} // if
	} // function

	/**
	 * SVN Kommando ausfuehren.
	 * @param string $sCommand SVN Kommando.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function execSvnCmd($sCommand)
	{
		$oLog = Log::getInstance();
		$oLog->writeLog(Log::HF_VARDUMP, 'command', $sCommand);

		exec($sCommand, $aData);

		$oLog->writeLog(Log::HF_VARDUMP, 'result lines', $aData);

		return $aData;
	} // function

	/**
	 * Daten aus Commit die geaender wurden.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getCommitChanged()
	{
		$sCommand  = $this->sSvnCmd;
		$sCommand .= ' changed ';
		$sCommand .= $this->sSvnLookParam;
		$sCommand .= ' ' . $this->sRepos;

		return $this->execSvnCmd($sCommand);
	} // function

	/**
	 * Infos zu dem Commit holen.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getCommitInfo()
	{
		$sCommand  = $this->sSvnCmd;
		$sCommand .= ' info';
		$sCommand .= $this->sSvnLookParam;
		$sCommand .= ' ' . $this->sRepos;

		return $this->execSvnCmd($sCommand);
	} // function

	/**
	 * Unterschiede des Commits (Diff).
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getCommitDiff()
	{
		$sCommand  = $this->sSvnCmd;
		$sCommand .= ' diff';
		$sCommand .= $this->sSvnLookParam;
		$sCommand .= ' ' . $this->sRepos;

		return $this->execSvnCmd($sCommand);
	} // function

	/**
	 * Schreiben des Contents aus der uebermittelten Datei.
	 * @param string $sFile    Datei aus TXN.
	 * @param string $sTmpFile Temporare Datei auf der Platte.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getContent($sFile, $sTmpFile)
	{
		$sCommand  = $this->sSvnCmd;
		$sCommand .= ' cat';
		$sCommand .= $this->sSvnLookParam;
		$sCommand .= ' ' . $this->sRepos;
		$sCommand .= ' ' . $sFile;
		$sCommand .= ' > ' . $sTmpFile;

		return $this->execSvnCmd($sCommand);
	} // function

	/**
	 * Property Liste Unterschiede des Commits (Diff).
	 * @param string $sItem Element fuer die Eigenschaften (Dir, File).
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @since  1.0.0
	 */
	public function getPropertyList($sItem)
	{
		$sCommand  = $this->sSvnCmd;
		$sCommand .= ' proplist';
		$sCommand .= $this->sSvnLookParam;
		$sCommand .= ' ' . $this->sRepos;
		$sCommand .= ' ' . $sItem;

		return $this->execSvnCmd($sCommand);
	} // function

	/**
	 * Property Liste Unterschiede des Commits (Diff).
	 * @param string $sItem     Element fuer die Eigenschaften (Dir, File).
	 * @param string $sProperty Name der Eigenschaft.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 * @since  1.0.0
	 */
	public function getPropertyValue($sItem, $sProperty)
	{
		$sCommand  = $this->sSvnCmd;
		$sCommand .= ' propget';
		$sCommand .= $this->sSvnLookParam;
		$sCommand .= ' ' . $this->sRepos;
		$sCommand .= ' ' . $sProperty;
		$sCommand .= ' ' . $sItem;

		return $this->execSvnCmd($sCommand);
	} // function
} // class
