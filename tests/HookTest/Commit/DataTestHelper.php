<?php
/**
 * Comment.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace HookTest\Commit;

use Hook\Commit\Object;
use Hook\Listener\AbstractObject;
use Hook\Listener\ListenerInterface;

/**
 * Comment.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 0.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class DataTestHelper extends AbstractObject implements ListenerInterface
{
    /**
     * Register the action and the file actions and file types that are needed.
     * Example for info listener type.
     * <pre>
     * return 'commit';
     * </pre>
     * Example for object listener type.
     * <pre>
     * return array(
     *           'action'     => 'commit',
     *           'fileaction' => array(
     *                            'A', 'U'
     *                           ),
     *           'extensions' => array(
     *                            'PHP'
     *                           )
     *          );
     * </pre>
     * Values for <i>action:</i> depend on the used vcs.
     * Values for <i>extensions:</i>
     * PHP, C, TXT, CSS, usw.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function register()
    {
        return array();
    }

    /**
     * Execute the action.
     * @param Object $oObject Object of the commit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function processAction(Object $oObject)
    {
        unset($oObject);
        return;
    }
}
