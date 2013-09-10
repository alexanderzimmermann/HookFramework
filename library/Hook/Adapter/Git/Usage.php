<?php
/**
 * Usage class for a little help output.
 * @category   Adapter
 * @package    Git
 * @subpackage Git
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter\Git;

/**
 * Usage class for a little help output.
 * @category   Adapter
 * @package    Git
 * @subpackage Git
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class Usage
{
    /**
     * Usage method that should be called.
     * @var string
     */
    private $sUsageMethod;

    /**
     * Constructor.
     * @param string $sMainType Main type hook (start, pre, post).
     * @param string $sSubType  Sub type (commit, lock, revprop-change).
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct($sMainType, $sSubType)
    {
        // Arguments are already checked, so parameters are ok.
        $this->sUsageMethod = 'get';
        $this->sUsageMethod .= ucfirst($sMainType);
        $this->sUsageMethod .= ucfirst(str_replace('-', '', $sSubType));
    }

    /**
     * Return usage text.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getUsage()
    {
        $sMethod = $this->sUsageMethod;

        // Head.
        $sUsage  = 'Call with the following parameters and order:' . "\n";
        $sUsage .= "\n";

        $aMethods = get_class_methods(get_class($this));

        if (in_array($sMethod, $aMethods) === true) {
            return $sUsage . $this->$sMethod();
        }

        return $sUsage . $this->getCommonUsage();
    }

    /**
     * Pre Commit Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getClientPrecommit()
    {
        $sUsage  = 'REPOSITORY Repository path (/path/to/project)' . "\n";
        $sUsage .= '           REPOSITORY=$(git rev-parse --show-toplevel)' . "\n";
        $sUsage .= 'Commit     HEAD, SHA1' . "\n";
        $sUsage .= 'Hook       client.pre-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOSITORY Commit client.pre-commit' . "\n";

        return $sUsage;
    }

    /**
     * Post Commit Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getClientPostcommit()
    {
        $sUsage  = 'REPOSITORY Repository path (/path/to/project)' . "\n";
        $sUsage .= '           REPOSITORY=$(git rev-parse --show-toplevel)' . "\n";
        $sUsage .= 'Commit     HEAD, SHA1' . "\n";
        $sUsage .= 'Hook       client.post-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOSITORY Commit client.post-commit' . "\n";

        return $sUsage;
    }

    /**
     * Usage for prepare-commit-msg and commit-msg hook.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getClientCommitmsg()
    {
        $sUsage  = 'REPOSITORY Repository path (/path/to/project)' . "\n";
        $sUsage .= '           REPOSITORY=$(git rev-parse --show-toplevel)' . "\n";
        $sUsage .= 'Commit     HEAD, SHA1' . "\n";
        $sUsage .= 'Hook       client.commit-msg' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOSITORY $commit $commitmsgfile client.commit-msg' . "\n";

        return $sUsage;
    }

    /**
     * Usage for prepare-commit-msg and commit-msg hook.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getClientPreparecommitmsg()
    {
        $sUsage = $this->getClientCommitmsg();

        return str_replace('client.commit-msg', 'client.prepare-commit-msg', $sUsage);
    }

    /**
     * Standard Usage, when main and sub type are not correct.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getCommonUsage()
    {
        $sUsage  = 'REPOSITORY Repository path (/path/to/project)' . "\n";
        $sUsage .= '           REPOSITORY=$(git rev-parse --show-toplevel)' . "\n";
        $sUsage .= 'Params     Parameters depending on hook type.' . "\n";
        $sUsage .= 'Hook       client.pre-commit, server.update' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Examples: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOSITORY HEAD client.HOOK-IDENTIFIER' . "\n";
        $sUsage .= '/path/to/hookframework/Hook $REPOSITORY $SHA1 server.HOOK-IDENTIFIER' . "\n";

        return $sUsage;
    }
}
