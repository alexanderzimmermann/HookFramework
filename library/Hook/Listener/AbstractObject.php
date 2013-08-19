<?php
/**
 * Abstract Class for object listener.
 * @category   Core
 * @package    Listener
 * @subpackage Object
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.1.0
 */

namespace Hook\Listener;

use Hook\Filter\ObjectFilter;
use Hook\Listener\ObjectInterface;

/**
 * Abstract Class for object listener.
 * @category   Core
 * @package    Listener
 * @subpackage Object
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 2.1.0
 */
abstract class AbstractObject implements ListenerInterface, ObjectInterface
{
    /**
     * Listener Name.
     * @var string
     */
    protected $sListener = 'Default Listener Name';

    /**
     * Configuration array.
     * @var array
     */
    protected $aCfg = array();

    /**
     * Object Filter.
     * @var ObjectFilter
     */
    protected $oObjectFilter;

    /**
     * Constructor.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct()
    {
        $this->oObjectFilter = new ObjectFilter();
    }

    /**
     * Set the configuration for the listener.
     * @param array $aCfg Configuration array for this listener.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function setConfiguration(array $aCfg)
    {
        $this->aCfg = $aCfg;
    }

    /**
     * Return listener name.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getListenerName()
    {
        return $this->sListener;
    }

    /**
     * Return Filter object of the listeners.
     * @return ObjectFilter
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getObjectFilter()
    {
        return $this->oObjectFilter;
    }
}
