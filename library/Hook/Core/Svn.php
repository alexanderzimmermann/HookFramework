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

namespace Hook\Core;

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
	 * SVN command  (incl. path).
	 * @var string
	 */
	private $sSvnCmd;

	/**
	 * Repository.
	 * @var string
	 */
	private $sRepos;

	/**
	 * Svn Param (Transaction [-t TXN] or Revision [-r REV]).
	 * @var string
	 */
	private $sSvnLookParam;

	/**
	 * Constructor.
	 * @param string $sSvnPath Path to the subversion executable.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct($sSvnPath)
	{
		$this->sSvnPath = $sSvnPath;
	} // function

	/**
	 * Initialize.
	 * @param Arguments $oArguments Command line arguments.
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
	 * Execut the svn command line.
	 * @param string $sCommand SVN Command.
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
	 * Get commit data was changed.
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
	 * Get information of that commit (user, text message).
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
	 * Get the difference of that commit.
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
	 * Write content from commited file.
	 * @param string $sFile    File from TXN.
	 * @param string $sTmpFile Temporary file on disk.
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
	 * Get list of properties to the item.
	 * @param string $sItem Element for the properties (Directory or file).
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
	 * Get the property value.
	 * @param string $sItem     Element for the properties (Directory or file).
	 * @param string $sProperty Name of property of value to get.
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
