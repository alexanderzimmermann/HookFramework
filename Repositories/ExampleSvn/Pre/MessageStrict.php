<?php
/**
 * Message Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace ExampleSvn\Pre;

use Hook\Commit\Info;
use Hook\Listener\AbstractInfo;

/**
 * Message Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class MessageStrict extends AbstractInfo
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
    }

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
    }

    /**
     * Check the commit message against our rules.
     * @param Info   $oInfo    Commit data object.
     * @param string $sMessage Text of commit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function checkMessage(Info $oInfo, $sMessage)
    {
        $sMessage = trim($sMessage);

        if ($sMessage === '') {
            $sErrorMessage = 'Please provide a comment for the commit' . "\n";
            $sErrorMessage .= 'The comment should be like:' . "\n";
            $sErrorMessage .= '+ If you add something.' . "\n";
            $sErrorMessage .= '- If you delete something.' . "\n";
            $sErrorMessage .= '* If you changed something.' . "\n";

            $oInfo->addError($sErrorMessage);

            return;
        }

        // Does comment start with  +, - or *?
        if (preg_match('/[\*+\-] /i', $sMessage) === 0) {
            $sErrorMessage = 'The comment should be like:' . "\n";
            $sErrorMessage .= '+ If you add something.' . "\n";
            $sErrorMessage .= '- If you delete something.' . "\n";
            $sErrorMessage .= '* If you changed something.' . "\n";

            $oInfo->addError($sErrorMessage);

            return;
        }

        $sMessage = preg_replace('[\*+\-] ', '', $sMessage);

        // A comment less than 10 signs is not really precisely.
        if (strlen($sMessage) < 10) {
            $sErrorMessage = 'The comment should be more precisely!';
            $oInfo->addError($sErrorMessage);
        }

        // Provide whole sentences not only fix, bug fix and so on.
        $aMessage = explode(' ', $sMessage);
        if (count($aMessage) < 3) {
            $sErrorMessage = 'Comment should contain whole sentences';
            $sErrorMessage .= "\n" . 'Subject, Predicate, Object, Point!';
            $oInfo->addError($sErrorMessage);
        }

        // Word length is less than 3 signs for each word.
        $iMax = count($aMessage);
        $iLen = 0;
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $iLen += strlen($aMessage[$iFor]);
        }

        $iSqr = round(($iLen / $iMax), 0);

        if ($iSqr <= 3) {
            $sErrorMessage = 'Comment should contain whole sentences and more precisely.';
            $sErrorMessage .= "\n" . 'Subject, Predicate, Object, Point!';

            $oInfo->addError($sErrorMessage);
        }
    }
}
