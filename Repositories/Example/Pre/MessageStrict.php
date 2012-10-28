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

use Core\Commit\CommitInfo;
use Core\Listener\ListenerInfoAbstract;

require_once 'Core/Listener/ListenerInfoAbstract.php';

/**
 * Message Listener.
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
class MessageStrict extends ListenerInfoAbstract
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Strict Commit Message';

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
	 * @param CommitInfo $oInfo Info des Commits.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitInfo $oInfo)
	{
		// Check message has at least 10 Chars.
		$sMessage = $oInfo->getMessage();

		$this->checkMessage($oInfo, $sMessage);
	} // function

	/**
	 * Check the commit message against our rules.
	 * @param CommitInfo $oInfo    Commit data object.
	 * @param string     $sMessage Text of commit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function checkMessage(CommitInfo $oInfo, $sMessage)
	{
		$sMessage = trim($sMessage);

		if ($sMessage === '')
		{
			$sErrorMessage  = 'Please provide a comment for the commit';
			$sErrorMessage .= 'The comment should be like:' . "\n";
			$sErrorMessage .= '+ If you add something.' . "\n";
			$sErrorMessage .= '- If you delete something.' . "\n";
			$sErrorMessage .= '* If you changed something.' . "\n";

			$oInfo->addError($sErrorMessage);

			return;
		} // if

		// Does comment start with  +, - or *?
		if (preg_match('/[\*+\-] /i', $sMessage) === 0)
		{
			$sErrorMessage  = 'The comment should be like:' . "\n";
			$sErrorMessage .= '+ If you add something.' . "\n";
			$sErrorMessage .= '- If you delete something.' . "\n";
			$sErrorMessage .= '* If you changed something.' . "\n";

			$oInfo->addError($sErrorMessage);
		} // if

		$sMessage = preg_replace('[\*+\-] ', '', $sMessage);

		// A comment less than 10 signs is not really precisely.
		if (strlen($sMessage) < 10)
		{
			$sErrorMessage = 'The comment should be more precisely!';
			$oInfo->addError($sErrorMessage);
		} // if

		// Provide whole sentences not only fix, bugfix and so on.
		$aMessage = explode(' ', $sMessage);
		if (count($aMessage) < 3)
		{
			$sErrorMessage  = 'Comment should contain whole sentences';
			$sErrorMessage .= "\n" . 'Subject, Praedicat, Object, Point!';
			$oInfo->addError($sErrorMessage);
		} // if

		// Word length is less than 3 sign for each word.
		$iMax = count($aMessage);
		$iLen = 0;
		for ($iFor = 0; $iFor < $iMax; $iFor++)
		{
			$iLen += strlen($aMessage[$iFor]);
		} // for

		$iSqr = round(($iLen / $iMax), 0);

		if ($iSqr <= 3)
		{
			$sErrorMessage  = 'Comment should contain whole sentences and more precisely.';
			$sErrorMessage .= "\n" . 'Subject, Praedicat, Object, Point!';

			$oInfo->addError($sErrorMessage);
		} // if

		return true;
	} // function
} // class
