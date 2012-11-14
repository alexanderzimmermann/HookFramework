<?php
/**
 * Message Listener.
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

use Hook\Commit\Data\Info;
use Hook\Listener\AbstractInfo;

/**
 * Message Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Message extends AbstractInfo
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Commit Message';

	/**
	 * Register the action.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function register()
	{
		return 'commit';
	} // function

	/**
	 * Execute the action.
	 * @param Info $oInfo Info des Commits.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(Info $oInfo)
	{
		// Check message has at least 10 Chars.
		$sMessage = $oInfo->getMessage();

		$this->checkMessage($oInfo, $sMessage);
	} // function

	/**
	 * Check that the message text matches the companies rules.
	 * @param Info   $oInfo    Commit data object.
	 * @param string $sMessage Text of commit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkMessage(Info $oInfo, $sMessage)
	{
		$sMessage = trim($sMessage);

		if ($sMessage === '')
		{
			$sErrorMessage  = 'Please provide a comment to this commit ';
			$sErrorMessage .= 'and use it as follows:' . "\n";
			$sErrorMessage .= '+ If something new is added.' . "\n";
			$sErrorMessage .= '- If something is deleted.' . "\n";
			$sErrorMessage .= '* If something is changed.' . "\n";

			$oInfo->addError($sErrorMessage);

			return;
		} // if

		// Is the commit started with +, - or * ?
		if (preg_match('/^[\*+\-]+ /i', $sMessage) === 0)
		{
			$sErrorMessage  = 'Please start the comment as follows:' . "\n";
			$sErrorMessage .= '+ If something new is added.' . "\n";
			$sErrorMessage .= '- If something is deleted.' . "\n";
			$sErrorMessage .= '* If something is changed.' . "\n";

			$oInfo->addError($sErrorMessage);
		} // if

		$sMessage = preg_replace('/^[\*+\-]+ /i', '', $sMessage);

		// Is the text at least 10 signs?
		if (strlen($sMessage) < 10)
		{
			$sErrorMessage = 'The comment should be more precisely!';
			$oInfo->addError($sErrorMessage);
		} // if
	} // function
} // class
