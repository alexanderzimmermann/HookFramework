<?php
/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Syntax extends ListenerObjectAbstract
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Syntax Pruefung';

	/**
	 * Registrieren auf die Aktion.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
		// Testdateien ignorieren.
		$sBaseFolder     = '/tmp/newfolder1/newfolder1_1/';
		$sParseErrorFile = $sBaseFolder . 'parse-error_file1.php';
		$this->oObjectFilter->addFileToFilter($sParseErrorFile);

		$sParseErrorFile = $sBaseFolder . 'parse-error_file2.php';
		$this->oObjectFilter->addFileToFilter($sParseErrorFile);

		return array(
				'action'     => 'commit',
				'fileaction' => array(
								 'A', 'U'
								),
				'extensions' => array('PHP'),
				'withdirs'   => false
			   );
	} // function

	/**
	 * Ausfuehren der Aktion.
	 * @param CommitObject $oObject Verz. / Datei-Objekt.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitObject $oObject)
	{
		$aLines = array();
		$sCmd   = 'php -l ' . $oObject->getTmpObjectPath() . ' 2>/dev/null';
		exec($sCmd, $aLines);

		if (empty($aLines) === true)
		{
			return;
		} // if

		$sMessage  = 'No syntax errors detected in ';
		$sMessage .= $oObject->getTmpObjectPath();

		if (count($aLines) === 1)
		{
			if ($aLines[0] === $sMessage)
			{
				return;
			} // if
		} // if

		unset($aLines[0]);
		$aLines[] = '';

		$oObject->addErrorLines($aLines);
	} // function
} // class
