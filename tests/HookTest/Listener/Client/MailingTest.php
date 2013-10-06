<?php
/**
 * Comment.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.4
 */

namespace HookTest\Listener\Failures\Client;

use Hook\Commit\Info;
use Hook\Commit\Object;

use ExampleGit\Client\Mailing;

require_once __DIR__ . '/../../../Bootstrap.php';

require_once __DIR__ . '/../../../../Repositories/ExampleGit/Client/Mailing.php';

/**
 * Comment.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.4
 */
class MailingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test object for Mailing.
     * @var Mailing
     */
    private $oMailingListener;

    /**
     * SetUp operations.
     * @return void
     */
    protected function setUp()
    {
        $this->oMailingListener = new Mailing();
    }

    /**
     * Test that object was created.
     * @return void
     */
    public function testObject()
    {
        $sClass = get_class($this->oMailingListener);
        $this->assertEquals('ExampleGit\Client\Mailing', $sClass);
    }

    /**
     * Test format of mail.
     * @return void
     */
    public function testMailBodyI()
    {
        // "Mock" Info- Object.
        $sUser = 'savannah';
        $sDate = '2013-12-30 14:56:23';
        $sText = '* Test comment for the Unit Tests.';

        $oInfo = new Info('abcdef01234567890', 0, $sUser, $sDate, $sText);

        // Test data for files.
        $aParams = array(
            'txn'    => 'abcdef01234567890',
            'rev'    => 0,
            'action' => '',
            'item'   => '',
            'real'   => '',
            'isdir'  => false,
            'ext'    => '',
            'props'  => array(),
            'lines'  => array(),
            'info'   => $oInfo
        );

        // Create file objects.
        $aObjects = array();

        // Addition of a file.
        $aParams['action'] = 'A';
        $aParams['item']   = 'doc/templates/git/pre-commit';
        $aParams['real']   = 'doc/templates/git/pre-commit';
        $aParams['isdir']  = false;
        $aObjects[]        = new Object($aParams);

        // Copy of a file into a new one.
        $aParams['action'] = 'C';
        $aParams['item']   = 'Commit/Base.php';
        $aParams['real']   = 'Commit/Base.php';
        $aObjects[]        = new Object($aParams);

        // Deletion of a file.
        $aParams['action'] = 'D';
        $aParams['item']   = 'tmp/newfolder/testfile.txt';
        $aParams['real']   = 'tmp/newfolder/testfile.txt';
        $aParams['isdir']  = false;
        $aObjects[]        = new Object($aParams);

        // Modification of the contents or mode of a file.
        $aParams['action'] = 'M';
        $aParams['item']   = 'Commit/Data.php';
        $aParams['real']   = 'Commit/Data.php';
        $aParams['isdir']  = false;
        $aObjects[]        = new Object($aParams);

        // Renaming of a file.
        $aParams['action'] = 'R';
        $aParams['item']   = 'Commit/Info.php';
        $aParams['real']   = 'Commit/Info.php';
        $aObjects[]        = new Object($aParams);

        // Change in the type of the file.
        $aParams['action'] = 'T';
        $aParams['item']   = 'Docs/languages/en/images/top.gif';
        $aParams['real']   = 'Docs/languages/en/images/top.gif';
        $aObjects[]        = new Object($aParams);

        // File is unmerged (you must complete the merge before it can be committed).
        $aParams['action'] = 'U';
        $aParams['item']   = 'Commit/Object.php';
        $aParams['real']   = 'Commit/Object.php';
        $aObjects[]        = new Object($aParams);

        $oInfo->setObjects($aObjects);

        $sMail = $this->oMailingListener->processAction($oInfo);
        $sExpected = file_get_contents(__DIR__ . '/_files/expected-mail-1.txt');

        $this->assertEquals($sExpected, $sMail);
    }

    /**
     * Test the format of the mail.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testMailBodyII()
    {
        // "Mock" Info- Object.
        $sUser = 'savannah';
        $sDate = '2013-12-30 14:56:23';
        $sText = '* Test comment for the Unit Tests.';

        $oInfo = new Info('abcdef01234567890', 0, $sUser, $sDate, $sText);

        // Test data for files.
        $aPath[] = 'Commit/Base.php';
        $aPath[] = 'Commit/Object.php';
        $aReal[] = 'Commit/Base.php';
        $aReal[] = 'Commit/Object.php';

        // Create file objects.
        $aParams = array(
            'txn'    => 'abcdef01234567890',
            'rev'    => 0,
            'action' => 'M',
            'item'   => $aPath[0],
            'real'   => $aReal[0],
            'ext'    => 'php',
            'isdir'  => false,
            'props'  => array(),
            'lines'  => array(),
            'info'   => $oInfo
        );

        $aObjects   = array();
        $aObjects[] = new Object($aParams);

        $aParams['item'] = $aPath[1];
        $aParams['real'] = $aReal[1];
        $aObjects[]      = new Object($aParams);

        $oInfo->setObjects($aObjects);

        $sMail     = $this->oMailingListener->processAction($oInfo);
        $sExpected = file_get_contents(__DIR__ . '/_files/expected-mail-2.txt');

        $this->assertEquals($sExpected, $sMail);
    }
}
