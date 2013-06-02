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
use Hook\Filter\ObjectFilter;
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
     * Test register data.
     * @var array
     */
    private $aRegister = array();

    /**
     * It is not nice, but it works.
     * @param array $aRegister Test register data.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setRegister(array $aRegister)
    {
        $this->aRegister = $aRegister;
    }

    /**
     * Register the action and the file actions and file types that are needed.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function register()
    {
        return $this->aRegister;
    }

    /**
     * Execute the action.
     * @param \Hook\Commit\Object|Object $oObject Object of the commit.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function processAction(Object $oObject)
    {
        unset($oObject);
        return;
    }

    /**
     *
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getObjectFilter()
    {
        return new ObjectFilter();
    }
}
