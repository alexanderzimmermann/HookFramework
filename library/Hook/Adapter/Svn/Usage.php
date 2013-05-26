<?php
/**
 * Usage class for a little help output.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter\Svn;

/**
 * Usage class for a little help output.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 2.1.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
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
        $sUsage = 'Call with the following parameters and order:' . "\n";
        $sUsage .= "\n";

        $aMethods = get_class_methods(get_class($this));

        if (in_array($sMethod, $aMethods) === true) {
            return $sUsage . $this->$sMethod();
        } // if

        return $sUsage . $this->getCommonUsage();
    }

    /**
     * Start Commit Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getStartCommit()
    {
        /**
        Variables in the hook that are shipped with subversion:
        [1] REPOS-PATH  (the path to this repository)
        [2] USER        (the authenticated user attempting to commit)
         */

        $sUsage = '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sUsage .= '$USER     Username of commit' . "\n";
        $sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOS $USER start-commit' . "\n";

        return $sUsage;
    }

    /**
     * Pre Commit Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getPreCommit()
    {
        /**
        Variables in the hook that are shipped with subversion:
        [1] REPOS-PATH  (the path to this repository)
        [2] TXN         (the name of the txn about to be committed)
         */

        $sUsage = '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sUsage .= '$TXN      Transaction (74-1)' . "\n";
        $sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOS $TXN pre-commit' . "\n";

        return $sUsage;
    }

    /**
     * Pre Revpropchange Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getPreRevpropchange()
    {
        /**
        Variables in the hook that are shipped with subversion:
        [1] REPOS-PATH  (the path to this repository)
        [2] REV         (the revision being tweaked)
        [3] USER        (the username of the person tweaking the property)
        [4] PROPNAME    (the property being set on the revision)
        [5] ACTION      (the property is being 'A'dded, 'M'odified,
        or 'D'eleted)

        [STDIN] PROPVAL  ** the new property value is passed via STDIN.
         */

        $sUsage = '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sUsage .= '$REV      Revision number that was created (74)' . "\n";
        $sUsage .= '$USER     Username of commit' . "\n";
        $sUsage .= '$PROPNAME Property name ' . "\n";
        $sUsage .= '$ACTION   Is property "A"dded, "M"odified oder ';
        $sUsage .= '"D"eleted' . "\n";
        $sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOS $REV $USER ';
        $sUsage .= '$SPROPNAME $ACTION pre-revprop-change' . "\n";

        return $sUsage;
    }

    /**
     * Pre Lock Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getPreLock()
    {
        /**
        Variables in the hook that are shipped with subversion:
        [1] REPOS-PATH   (the path to this repository)
        [2] PATH         (the path in the repository about to be locked)
        [3] USER         (the user creating the lock)
         */

        $sUsage = '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sUsage .= '$PATH     Path in repository that is locked.' . "\n";
        $sUsage .= '$USER     Username of commit' . "\n";
        $sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sUsage .= "\n\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOS $PATH $USER pre-lock' . "\n";

        return $sUsage;
    }

    /**
     * Pre Unlock Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getPreUnlock()
    {
        /**
        Variables in the hook that are shipped with subversion:
        [1] REPOS-PATH   (the path to this repository)
        [2] PATH         (the path in the repository about to be locked)
        [3] USER         (the user creating the lock)
         */

        $sUsage = '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sUsage .= '$PATH     Path in repository that is locked.' . "\n";
        $sUsage .= '$USER     Username of commit' . "\n";
        $sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOS $PATH $USER pre-unlock' . "\n";

        return $sUsage;
    }

    /**
     * Post Commit Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getPostCommit()
    {
        /**
        Variables in the hook that are shipped with subversion:
        [1] REPOS-PATH   (the path to this repository)
        [2] REV          (the number of the revision just committed)
         */

        $sUsage = '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sUsage .= '$REV      Revision number that was created (74)' . "\n";
        $sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOS $REV post-commit' . "\n";

        return $sUsage;
    }

    /**
     * Post Revpropchange Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getPostRevpropchange()
    {
        /**
        Variables in the hook that are shipped with subversion:
        [1] REPOS-PATH  (the path to this repository)
        [2] REV         (the revision being tweaked)
        [3] USER        (the username of the person tweaking the property)
        [4] PROPNAME    (the property being set on the revision)
        [5] ACTION      (the property is being 'A'dded, 'M'odified,
        or 'D'eleted)

        [STDIN] PROPVAL  ** the new property value is passed via STDIN.
         */

        $sUsage = '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sUsage .= '$REV      Revision number that will be created (74-1)' . "\n";
        $sUsage .= '$USER     Username of commit' . "\n";
        $sUsage .= '$PROPNAME Property name ' . "\n";
        $sUsage .= '$ACTION   Is property "A"dded, "M"odified oder ';
        $sUsage .= '"D"eleted' . "\n";
        $sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOS $REV $USER ';
        $sUsage .= '$SPROPNAME $ACTION post-revprop-change' . "\n";

        return $sUsage;
    }

    /**
     * Post Lock Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getPostLock()
    {
        /**
        Variables in the hook that are shipped with subversion:
        [1] REPOS-PATH   (the path to this repository)
        [2] USER         (the user who created the lock)
         */

        $sUsage = '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sUsage .= '$USER     Username of commit' . "\n";
        $sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOS $USER post-lock' . "\n";

        return $sUsage;
    }

    /**
     * Post Unlock Hook Usage.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getPostUnlock()
    {
        /**
        Variables in the hook that are shipped with subversion:
        [1] REPOS-PATH   (the path to this repository)
        [2] USER         (the user who destroyed the lock)
         */

        $sUsage = '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sUsage .= '$USER     Username of commit' . "\n";
        $sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Example: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOS $USER post-unlock' . "\n";

        return $sUsage;
    }

    /**
     * Standard Usage, when main and sub type are not correct.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function getCommonUsage()
    {
        $sUsage = '$REPOS    Repository path (/var/svn/project)' . "\n";
        $sUsage .= '$Params   Parameters depending on hook type.' . "\n";
        $sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
        $sUsage .= "\n";
        $sUsage .= 'Examples: ';
        $sUsage .= '/path/to/hookframework/Hook $REPOS $TXN pre-commit' . "\n";
        $sUsage .= '/path/to/hookframework/Hook $REPOS $REV post-commit' . "\n";

        return $sUsage;
    }
}
