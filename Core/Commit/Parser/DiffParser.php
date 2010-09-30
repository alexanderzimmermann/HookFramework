<?php
/**
 * Parser for the differences of the transaction / revision.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once 'Core/Commit/Parser/Lines.php';
require_once 'Core/Commit/Parser/Properties.php';

/**
 * Parser for the differences of the whole commit.
 *
 * Divides the lines into its parts of each file in the commit.
 * @category   Core
 * @package    Commit
 * @subpackage Parser
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class DiffParser
{
	/**
	 * The line parser object.
	 * @var Lines
	 */
	private $oLines;

	/**
	 * The property parser object.
	 * @var Properties
	 */
	private $oProperties;

	/**
	 * Kopf fuer Zeilenaenderung.
	 * @var string
	 */
	private $sLinesHead;

	/**
	 * Kopf fuer Eigenscahftsaenderungen.
	 * @var string
	 */
	private $sPropertyHead;

	/**
	 * Geanderte Objekte.
	 * @var array
	 */
	private $aObjects;

	/**
	 * Differenz Zeilen des Commits.
	 * @var array
	 */
	private $aLines = array();

	/**
	 * Parsed Lines.
	 * @var array
	 */
	private $aParsedLines = array();

	/**
	 * Konstruktor.
	 * @param array $aObjects Directories and Files in the commit / transaction.
	 * @param array $aLines   Difference lines of the commit / transaction.
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct(array $aObjects, array $aLines)
	{
		$this->oLines      = new Lines();
		$this->oProperties = new Properties();

		$this->sLinesHead    = str_repeat('=', 67);
		$this->sPropertyHead = str_repeat('_', 67);

		$this->aObjects = $aObjects;
		$this->aLines   = $aLines;
	} // function

	/**
	 * Parse the diff lines of that commit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function parse()
	{
		$iOffset     = false;
		$aLines      = array();
		$bLines      = false;
		$bProperties = false;

		foreach ($this->aLines as $iLine => $sLine)
		{
			if ($sLine === $this->sLinesHead)
			{
				$this->handle($iOffset, $aLines, array($bLines, $bProperties));

				$bLines      = true;
				$bProperties = false;
				$iOffset     = $this->getFile($this->aLines[($iLine - 1)]);
				$aLines      = array();
			} // if

			if ($sLine === $this->sPropertyHead)
			{
				$this->handle($iOffset, $aLines, array($bLines, $bProperties));

				$bLines      = false;
				$bProperties = true;
				$iOffset     = $this->getFile($this->aLines[($iLine - 1)]);
				$aLines      = array();
			} // if

			$aLines[] = $sLine;
		} // foreach

		$this->handle($iOffset, $aLines, array($bLines, $bProperties));
	} // function

	/**
	 * Handle the changes between lines and or properties blocks.
	 * @param integer $iOffset Offset of the file.
	 * @param array   $aLines  The collected lines of the block.
	 * @param array   $aBlock  Which block was active.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function handle($iOffset, array $aLines, array $aBlock)
	{
		if ((false !== $iOffset) && (false === empty($aLines)))
		{
			// First Line is always the -- oder ==.
			array_shift($aLines);

			// Last line is the info of the next file.
			if ('' !== $aLines[(count($aLines) - 1)])
			{
				array_pop($aLines);
			} // if

			// Lines were a change lines block.
			if (true === $aBlock[0])
			{
				$this->oLines->parse($iOffset, $aLines);
			} // if

			if (true === $aBlock[1])
			{
				$this->oProperties->parse($iOffset, $aLines);
			} // if
		} // if
	} // function

	/**
	 * Determine the file of the changed lines / properties.
	 * @param string $sLine The line of the changed lines / properties.
	 * @return integer
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getFile($sLine)
	{
		$aParts  = explode(':', $sLine);
		$sFile   = trim($aParts[1]);
		$iOffset = array_search($sFile, $this->aObjects);

		return $iOffset;
	} // function

	/**
	 * Returns the lines objects.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getLines()
	{
		return $this->oLines->getLines();
	} // function

	/**
	 * Returns the property objects.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getProperties()
	{
		return $this->oProperties->getProperties();
	} // function
} // class
