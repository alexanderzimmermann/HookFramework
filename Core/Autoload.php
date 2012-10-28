<?php
/**
 * Autoload function.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 2.0.0
 */

/**
 * Autoload function.
 * @param $class Class to load.
 * @return void
 */
function hfautoload($class)
{
	static $classes = null;

	if ($classes === null)
	{
		$classes = array(
			'core\\arguments' => '/Arguments.php',
			'core\\commit\\commitbase' => '/Commit/CommitBase.php',
			'core\\commit\\commitdata' => '/Commit/CommitData.php',
			'core\\commit\\commitinfo' => '/Commit/CommitInfo.php',
			'core\\commit\\commitobject' => '/Commit/CommitObject.php',
			'core\\commit\\commitparser' => '/Commit/CommitParser.php',
			'core\\commit\\diff\\diff_lines' => '/Commit/Diff/Lines.php',
			'core\\commit\\diff\\diff_property' => '/Commit/Diff/Property.php',
			'core\\commit\\parser\\diffparser' => '/Commit/Parser/DiffParser.php',
			'core\\commit\\parser\\lines' => '/Commit/Parser/Lines.php',
			'core\\commit\\parser\\properties' => '/Commit/Parser/Properties.php',
			'core\\error' => '/Error.php',
			'core\\filter\\filter' => '/Filter/Filter.php',
			'core\\filter\\objectfilter' => '/Filter/ObjectFilter.php',
			'core\\hookmain' => '/Hook.php',
			'core\\listener\\listenerinfo' => '/Listener/ListenerInfo.php',
			'core\\listener\\listenerinfoabstract' => '/Listener/ListenerInfoAbstract.php',
			'core\\listener\\listenerobject' => '/Listener/ListenerObject.php',
			'core\\listener\\listenerobjectabstract' => '/Listener/ListenerObjectAbstract.php',
			'core\\listener\\listenerparser' => '/Listener/ListenerParser.php',
			'core\\log' => '/Log.php',
			'core\\repository' => '/Repository.php',
			'core\\svn' => '/Svn.php',
			'core\\usage' => '/Usage.php'
		);
	} // if

	$cn = strtolower($class);

	if (isset($classes[$cn]))
	{
		require __DIR__ . $classes[$cn];
	} // if
} // function

// Register spl autoload.
spl_autoload_register('hfautoload');
