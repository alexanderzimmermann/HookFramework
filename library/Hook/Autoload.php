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
 * Auto load function.
 * @param $class Class to load.
 * @return void
 */
function hfautoload($class)
{
	static $classes = null;

	if ($classes === null)
	{
		$classes = array(
			'hook\\commit\\base' => '/Commit/Base.php',
			'hook\\commit\\data' => '/Commit/Data.php',
			'hook\\commit\\commitinfo' => '/Commit/CommitInfo.php',
			'hook\\commit\\commitobject' => '/Commit/CommitObject.php',
			'hook\\commit\\parser' => '/Commit/Parser.php',
			'hook\\commit\\diff\\lines' => '/Commit/Diff/Lines.php',
			'hook\\commit\\diff\\property' => '/Commit/Diff/Property.php',
			'hook\\commit\\parser\\lines' => '/Commit/Parser/Lines.php',
			'hook\\commit\\parser\\parser' => '/Commit/Parser/Parser.php',
			'hook\\commit\\parser\\properties' => '/Commit/Parser/Properties.php',
			'hook\\core\\arguments' => '/Core/Arguments.php',
			'hook\\core\\error' => '/Core/Error.php',
			'hook\\core\\hook' => '/Core/Hook.php',
			'hook\\core\\log' => '/Core/Log.php',
			'hook\\core\\repository' => '/Core/Repository.php',
			'hook\\core\\svn' => '/Core/Svn.php',
			'hook\\core\\usage' => '/Core/Usage.php',
			'hook\\filter\\filter' => '/Filter/Filter.php',
			'hook\\filter\\objectfilter' => '/Filter/ObjectFilter.php',
			'hook\\listener\\info' => '/Listener/Info.php',
			'hook\\listener\\infoabstract' => '/Listener/InfoAbstract.php',
			'hook\\listener\\object' => '/Listener/Object.php',
			'hook\\listener\\objectabstract' => '/Listener/ObjectAbstract.php',
			'hook\\listener\\listenerparser' => '/Listener/ListenerParser.php'
		);
	} // if

	$cn = strtolower($class);

	if (isset($classes[$cn]))
	{
		require __DIR__ . $classes[$cn];
	} // if
} // function

// Register spl auto load.
spl_autoload_register('hfautoload');
