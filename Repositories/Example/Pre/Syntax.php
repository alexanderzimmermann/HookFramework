<?php
/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Example\Pre;

use Core\Commit\CommitObject;
use Core\Listener\ListenerObjectAbstract;

/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Syntax extends ListenerObjectAbstract
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Syntax check';

	/**
	 * Register the action.
	 * @return array
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
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
	 * Execute the action.
	 * @param CommitObject $oObject Directory / File-Object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitObject $oObject)
	{
		$aLines = array();
		$sCmd   = 'php -l ' . $oObject->getTmpObjectPath() . ' 2>&1';
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
