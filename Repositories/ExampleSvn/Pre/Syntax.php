<?php
/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace ExampleSvn\Pre;

use Hook\Commit\Object;
use Hook\Listener\AbstractObject;

/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Syntax extends AbstractObject
{
    /**
     * Listener Name.
     * @var string
     */
    protected $sListener = 'Syntax check';

    /**
     * Register the action.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function register()
    {
        return array(
            'action'     => 'commit',
            'fileaction' => array(
                'A', 'U'
            ),
            'extensions' => array('PHP'),
            'withdirs'   => false
        );
    }

    /**
     * Execute the action.
     * @param Object $oObject Directory / File-Object.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function processAction(Object $oObject)
    {
        $aLines = array();
        $sCmd   = 'php -l ' . $oObject->getTmpObjectPath() . ' 2>&1';

        exec($sCmd, $aLines);

        if (true === empty($aLines)) {
            return;
        }

        $sMessage = 'No syntax errors detected in ';
        $sMessage .= $oObject->getTmpObjectPath();

        if (count($aLines) === 1) {
            if ($aLines[0] === $sMessage) {
                return;
            }
        }

        $oObject->addErrorLines($aLines);
    }
}
