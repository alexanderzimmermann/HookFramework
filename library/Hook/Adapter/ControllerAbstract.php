<?php
/**
 * Controller abstract class.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter;

use Hook\Adapter\Svn\Arguments;
use Hook\Adapter\Svn\Command;
use Hook\Commit\Info;
use Hook\Commit\Object;
use Hook\Commit\Data;

/**
 * Abstract class for the controller in the adapter.
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
abstract class ControllerAbstract
{
    /**
     * Arguments from hook call.
     * @var Arguments
     */
    protected $oArguments;

    /**
     * The command object for executing VCS commands.
     * @var Command
     */
    protected $oCommand;

    /**
     * Data Object.
     * @var Data
     */
    protected $oData;

    /**
     * Parsed changed items.
     * @var array
     */
    protected $aChangedItems;

    /**
     * Parse changed data.
     * @var array
     */
    protected $aChangedData;

    /**
     * Constructor.
     * @param Arguments $oArguments Arguments object.
     * @param Command   $oCommand       Subversion object.
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function __construct(Arguments $oArguments, Command $oCommand)
    {
        $this->aChangedItems = array();
        $this->aChangedData  = array();

        $this->oArguments = $oArguments;
        $this->oCommand   = $oCommand;
    }

    /**
     * Return commit data object.
     * @return Data
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getCommitDataObject()
    {
        return $this->oData;
    }
}
