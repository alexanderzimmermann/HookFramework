<?php
/**
 * Id svn keyword.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Example\Pre;

use Hook\Commit\CommitObject;
use Hook\Listener\ObjectAbstract;

/**
 * Id svn keyword.
 *
 * The command svnlook propget /var/svn/xxxx svn:keywords
 * /trunk/path/to/file.php returns the "Id", and so we can check that
 * that the property is set.
 * @category   Listener
 * @package    Pre
 * @subpackage Pre
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Id extends ObjectAbstract
{
	/**
	 * Listener Name.
	 * @var string
	 */
	protected $sListener = 'Id keyword check';

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
				'extensions' => array(),
				'withdirs'   => false
			   );
	} // function

	/**
	 * Execute the action.
	 * @param CommitObject $oObject Directory / File object.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function processAction(CommitObject $oObject)
	{
		$sAction     = $oObject->getAction();
		$aProperties = $oObject->getChangedProperties();

		// On add action check for svn:keywords and the "Id" tag.
		if ('A' === $sAction)
		{
			if (false === isset($aProperties['svn:keywords']))
			{
				$oObject->addError('Please add the "svn:keywords - Id" tag to the file.');
			}
			else
			{
				$sValue = $aProperties['svn:keywords']->getNewValue();

				if (false === strpos($sValue, 'Id'))
				{
					$oObject->addError('Please add the "Id" value to the svn:keywords tag.');
				} // if
			} // if
		} // if

		if ('U' === $sAction)
		{
			if (true === isset($aProperties['svn:keywords']))
			{
				$sValue = $aProperties['svn:keywords']->getNewValue();

				if (false === strpos($sValue, 'Id'))
				{
					$oObject->addError('Do not delete the "Id" tag of the file.');
				} // if
			} // if
		} // if
	} // function
} // class
