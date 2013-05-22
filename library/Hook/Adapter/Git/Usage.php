<?php
/**
 * Usage class for a little help output.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 3.0.0
 */

namespace Hook\Adapter\Git;

/**
 * Usage class for a little help output.
 * @category   Core
 * @package    Main
 * @subpackage Main
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
    }
}
