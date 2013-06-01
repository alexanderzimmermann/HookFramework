<?php
/**
 *  This is the main parser for the git adapter.
 * @category   Adapter
 * @package    Adapter
 * @subpackage Git
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter\Git;

use Hook\Adapter\ParserAbstract;

/**
 * This is the main parser for the git adapter.
 * @category   Adapter
 * @package    Adapter
 * @subpackage Git
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class Controller extends ControllerAbstract
{
    /**
     * Available adapter actions.
     * @var array
     */
    protected $aAdapterActions = array('M', 'C', 'R', 'A', 'D', 'U');

    /**
     * Constructor.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct()
    {
    }

    /**
     * Start parsing
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parse()
    {
        $this->oData = new Data($this->aAdapterActions);
    }
}
