<?php
/**
 * Usage-Object Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Adapter\Svn;

use Hook\Adapter\Svn\Usage;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Usage-Object Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Svn
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class UsageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test for StartCommit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsageStartCommit()
    {
        $oUsage = new Usage('start', 'commit');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$USER     Username of commit' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $USER start-commit' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for PreCommit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsagePreCommit()
    {
        $oUsage = new Usage('pre', 'commit');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$TXN      Transaction (74-1)' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $TXN pre-commit' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for PreRevpropchange.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsagePreRevpropchange()
    {
        $oUsage = new Usage('pre', 'revprop-change');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$REV      Revision number that was created (74)' . "\n";
        $sExpected .= '$USER     Username of commit' . "\n";
        $sExpected .= '$PROPNAME Property name ' . "\n";
        $sExpected .= '$ACTION   Is property "A"dded, "M"odified ';
        $sExpected .= 'oder "D"eleted' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $REV $USER ';
        $sExpected .= '$SPROPNAME $ACTION pre-revprop-change' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for den PreLock.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsagePreLock()
    {
        $oUsage = new Usage('pre', 'lock');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$PATH     Path in repository that is locked.' . "\n";
        $sExpected .= '$USER     Username of commit' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $PATH $USER pre-lock' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for PreUnlock.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsagePreUnlock()
    {
        $oUsage = new Usage('pre', 'unlock');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$PATH     Path in repository that is locked.' . "\n";
        $sExpected .= '$USER     Username of commit' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $PATH $USER pre-unlock' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for PostCommit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsagePostCommit()
    {
        $oUsage = new Usage('post', 'commit');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$REV      Revision number that was created (74)' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $REV post-commit' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for PostRevpropchange.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsagePostRevpropchange()
    {
        $oUsage = new Usage('post', 'revprop-change');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$REV      Revision number that will be created (74-1)' . "\n";
        $sExpected .= '$USER     Username of commit' . "\n";
        $sExpected .= '$PROPNAME Property name ' . "\n";
        $sExpected .= '$ACTION   Is property "A"dded, "M"odified ';
        $sExpected .= 'oder "D"eleted' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $REV $USER ';
        $sExpected .= '$SPROPNAME $ACTION post-revprop-change' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for PostLock.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsagePostLock()
    {
        $oUsage = new Usage('post', 'lock');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$USER     Username of commit' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $USER post-lock' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for PostUnlock.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsagePostUnlock()
    {
        $oUsage = new Usage('post', 'unlock');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$USER     Username of commit' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $USER post-unlock' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for common usage.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsageCommon()
    {
        $oUsage = new Usage('', '');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sExpected .= '$Params   Parameters depending on hook type.' . "\n";
        $sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Examples: ';
        $sExpected .= '/path/to/hookframework/Hook $REPOS $TXN pre-commit' . "\n";
        $sExpected .= '/path/to/hookframework/Hook $REPOS $REV post-commit' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }
}
