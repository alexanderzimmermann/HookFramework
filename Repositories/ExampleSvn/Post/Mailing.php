<?php
/**
 * Mailing Listener.
 * @category   Listener
 * @package    Post
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace ExampleSvn\Post;

use stdClass;
use Hook\Commit\Info;
use Hook\Listener\AbstractInfo;

/**
 * Mailing Listener.
 * @category   Listener
 * @package    Post
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Mailing extends AbstractInfo
{
    /**
     * Listener Name.
     * @var string
     */
    protected $sListener = 'Post Commit Mailing';

    /**
     * Commit info object.
     * @var Info
     */
    private $oInfo;

    /**
     * List of modifiers for mail formatting.
     * @var array
     */
    private $aActions = array(
                         'added', 'updated', 'deleted'
                        );

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
     * @return string
     */
    public function processAction(Info $oInfo)
    {
        $this->oInfo = $oInfo;

        $sMailBody = $this->getInfoMailHead();
        $sMailBody .= $this->getObjectsMailBody();

        // Mail head.
        $sHeader = 'From: webmaster@example.com' . "\r\n";
        $sHeader .= 'Reply-To: webmaster@example.com' . "\r\n";
        $sHeader .= 'Content-Type: text/plain; char-set=UTF-8' . "\r\n";
        $sHeader .= 'X-Mailer: PHP/' . phpversion();

        mail('alex@aimmermann.com', 'Git Commit', $sMailBody, $sHeader);

        return $sMailBody;
    }

    /**
     * Prepare commit info.
     * @return string
     */
    private function getInfoMailHead()
    {
        $sHead  = 'Date Time : ' . $this->oInfo->getDateTime() . "\n";
        $sHead .= 'User      : ' . $this->oInfo->getUser() . "\n";
        $sHead .= 'Comment   : ' . $this->oInfo->getMessage() . "\n";

        $sHead .= str_repeat('=', 80) . "\n";

        return $sHead;
    }

    /**
     * Prepare file list for mail.
     * @param array $aObjects List of file objects from commit.
     * @return stdClass
     */
    protected function prepareFileList(array $aObjects)
    {
        $oResult = new stdClass;
        $oResult->aFileSummary = array();

        foreach ($this->aActions as $sAction) {
            $oResult->aFileSummary[$sAction] = array();
        }

        $oResult->sFileList = '';
        $iMax               = count($aObjects);

        for ($iFor = 0; $iFor < $iMax; $iFor++) {

            /** @var Object $oObject */
            $oObject    = $aObjects[$iFor];
            $sObject    = $oObject->getObjectPath();
            $oResult->sFileList .= $sObject;

            foreach ($this->aActions as $sAction) {
                if (ucfirst($sAction[0]) === $oObject->getAction()) {
                    $oResult->aFileSummary[$sAction][] = $sObject;
                    $oResult->sFileList .= ' (' . $sAction . ')';
                }
            }

            $oResult->sFileList .= "\n";
        }

        return $oResult;
    }

    /**
     * Prepare file information for mail body.
     * @return string
     */
    private function getObjectsMailBody()
    {
        $oResult = $this->prepareFileList($this->oInfo->getObjects());

        $sMailBody  = 'Directories, File Information\'s:' . "\n";
        $sMailBody .= str_repeat('-', 40) . "\n";

        foreach ($this->aActions as $sAction) {
            if (false === empty($oResult->aFileSummary[$sAction])) {
                $sMailText  = ucfirst($sAction) . str_repeat(' ', (8 - strlen($sAction)));
                $sMailBody .= $sMailText . ': ' . count($oResult->aFileSummary[$sAction]) . "\n";
                $sMailBody .= implode("\n", $oResult->aFileSummary[$sAction]) . "\n\n";
            }
        }

        $sMailBody .= "\n" . $oResult->sFileList;

        return $sMailBody;
    }
}
