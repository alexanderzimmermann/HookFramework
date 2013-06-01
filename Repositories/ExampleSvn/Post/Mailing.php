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
 * @version    Release: 2.1.0
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
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function processAction(Info $oInfo)
    {
        $this->oInfo = $oInfo;

        $sMailBody = $this->getInfoMailHead();
        $sMailBody .= $this->getObjectsMailBody();

        // Mailen.
        $sHeader = 'From: webmaster@example.com' . "\r\n";
        $sHeader .= 'Reply-To: webmaster@example.com' . "\r\n";
        $sHeader .= 'Content-Type: text/plain; char-set=UTF-8' . "\r\n";
        $sHeader .= 'X-Mailer: PHP/' . phpversion();

        mail('alex@aimmermann.com', 'SVN Commit', $sMailBody, $sHeader);

        return $sMailBody;
    }

    /**
     * Aufbereiten Commit Info.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getInfoMailHead()
    {
        $sHead = 'Date Time : ' . $this->oInfo->getDateTime() . "\n\n";
        $sHead .= 'User      : ' . $this->oInfo->getUser() . "\n";
        $sHead .= "\n";
        $sHead .= 'Comment   : ' . $this->oInfo->getMessage() . "\n\n";

        $sHead .= str_repeat('=', 80) . "\n";

        return $sHead;
    }

    /**
     * Prepare file information for mail body.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getObjectsMailBody()
    {
        $aAdded   = array();
        $aUpdated = array();
        $aDeleted = array();

        $aObjects = $this->oInfo->getObjects();
        $iMax     = count($aObjects);

        $sFileList = '';
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $sFileList .= $aObjects[$iFor]->getObjectPath();

            if ($aObjects[$iFor]->getAction() === 'A') {
                $aAdded[] = $aObjects[$iFor]->getObjectPath();
                $sFileList .= ' (new)';
            }

            if ($aObjects[$iFor]->getAction() === 'U') {
                $aUpdated[] = $aObjects[$iFor]->getObjectPath();
                $sFileList .= ' (updated)';
            }

            if ($aObjects[$iFor]->getAction() === 'D') {
                $aDeleted[] = $aObjects[$iFor]->getObjectPath();
                $sFileList .= ' (deleted)';
            }

            $sFileList .= "\n";
        } // for

        $sMailBody = 'Directories, Fileinformations:' . "\n";
        $sMailBody .= str_repeat('-', 40) . "\n";

        if (empty($aAdded) === false) {
            $sMailBody .= 'Added   : ' . count($aAdded) . "\n";
            $sMailBody .= implode("\n", $aAdded) . "\n";
        }

        if (empty($aUpdated) === false) {
            $sMailBody .= 'Updated : ' . count($aUpdated) . "\n";
            $sMailBody .= implode("\n", $aUpdated) . "\n";
        }

        if (empty($aDeleted) === false) {
            $sMailBody .= 'Deleted : ' . count($aDeleted) . "\n";
            $sMailBody .= implode("\n", $aDeleted) . "\n";
        }

        $sMailBody .= "\n" . $sFileList;

        return $sMailBody;
    }
}
