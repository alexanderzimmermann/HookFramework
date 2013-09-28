<?php
/**
 * Usage-Object Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Git
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace HookTest\Adapter\Git;

use Hook\Adapter\Git\Usage;

require_once __DIR__ . '/../../../Bootstrap.php';

/**
 * Usage-Object Tests.
 * @category   Tests
 * @package    Adapter
 * @subpackage Git
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class UsageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test client prepare-commit-msg
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testClientPrepareCommitMsg()
    {
        $oUsage = new Usage('client', 'prepare-commit-msg');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'repository Repository path (/path/to/project)' . "\n";
        $sExpected .= '           repository=$(git rev-parse --show-toplevel)' . "\n";
        $sExpected .= 'Commit     HEAD, SHA1' . "\n";
        $sExpected .= 'Hook       client.prepare-commit-msg' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $repository $commit $commitmsgfile client.prepare-commit-msg' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for PreCommit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsageClientPreCommit()
    {
        $oUsage = new Usage('client', 'pre-commit');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'repository Repository path (/path/to/project)' . "\n";
        $sExpected .= '           repository=$(git rev-parse --show-toplevel)' . "\n";
        $sExpected .= 'Commit     HEAD, SHA1' . "\n";
        $sExpected .= 'Hook       client.pre-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $repository Commit client.pre-commit' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for PostCommit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsageClientPostCommit()
    {
        $oUsage = new Usage('client', 'post-commit');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'repository Repository path (/path/to/project)' . "\n";
        $sExpected .= '           repository=$(git rev-parse --show-toplevel)' . "\n";
        $sExpected .= 'Commit     HEAD, SHA1' . "\n";
        $sExpected .= 'Hook       client.post-commit' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $repository Commit client.post-commit' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for PostCommit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsageClientCommitMsg()
    {
        $oUsage = new Usage('client', 'commit-msg');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'repository Repository path (/path/to/project)' . "\n";
        $sExpected .= '           repository=$(git rev-parse --show-toplevel)' . "\n";
        $sExpected .= 'Commit     HEAD, SHA1' . "\n";
        $sExpected .= 'Hook       client.commit-msg' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Example: ';
        $sExpected .= '/path/to/hookframework/Hook $repository $commit $commitmsgfile client.commit-msg' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }

    /**
     * Test for common usage.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function testUsageCommon()
    {
        $oUsage = new Usage('client', 'something');

        // Expected text.
        $sExpected = 'Call with the following parameters and order:' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'repository Repository path (/path/to/project)' . "\n";
        $sExpected .= '           repository=$(git rev-parse --show-toplevel)' . "\n";
        $sExpected .= 'Params     Parameters depending on hook type.' . "\n";
        $sExpected .= 'Hook       client.pre-commit, server.update' . "\n";
        $sExpected .= "\n";
        $sExpected .= 'Examples: ';
        $sExpected .= '/path/to/hookframework/Hook $repository HEAD client.HOOK-IDENTIFIER' . "\n";
        $sExpected .= '/path/to/hookframework/Hook $repository $SHA1 server.HOOK-IDENTIFIER' . "\n";

        $this->assertEquals($sExpected, $oUsage->getUsage());
    }
}
