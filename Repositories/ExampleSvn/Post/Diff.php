<?php
/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Post
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: Diff.php 173 2010-02-27 23:06:48Z alexander $
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace ExampleSvn\Post;

use Hook\Commit\Object;
use Hook\Listener\AbstractObject;

/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Post
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Diff extends AbstractObject
{
    /**
     * Listener Name.
     * @var string
     */
    protected $sListener = 'Post Commit Diff';

    /**
     * Register the action.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function register()
    {
        return array(
            'action'     => 'commit',
            'fileaction' => array(),
            'extensions' => array(),
            'withdirs'   => true
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
        error_log('POST');
    }
}

