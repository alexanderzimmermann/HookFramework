<?php
/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Post
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: Diff.php 173 2010-02-27 23:06:48Z alexander $
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Example\Post;

use Core\Commit\CommitObject;
use Core\Listener\ListenerObjectAbstract;

/**
 * Style Guide Listener.
 * @category   Listener
 * @package    Post
 * @subpackage Post
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Diff extends ListenerObjectAbstract
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
	} // function

	/**
	 * Execute the action.
	 * @param CommitObject $oObject Verz. / Datei-Objekt.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitObject $oObject)
	{
		error_log('POST');
	} // function
} // class
