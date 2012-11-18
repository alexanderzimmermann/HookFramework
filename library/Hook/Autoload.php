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
 * @since      File available since Release 2.1.0
 */

/**
 * Auto load function.
 * @param string $sClass Class to load.
 * @return void
 */
function hfautoload($sClass)
{
	static $aClasses = null;

	if ($aClasses === null)
	{
		$aClasses = array(
			'hook\\commit\\data' => '/Commit/Data.php',
			'hook\\commit\\data\\base' => '/Commit/Data/Base.php',
			'hook\\commit\\data\\info' => '/Commit/Data/Info.php',
			'hook\\commit\\data\\object' => '/Commit/Data/Object.php',
			'hook\\commit\\diff\\lines' => '/Commit/Diff/Lines.php',
			'hook\\commit\\diff\\property' => '/Commit/Diff/Property.php',
			'hook\\commit\\parser' => '/Commit/Parser.php',
			'hook\\commit\\parser\\lines' => '/Commit/Parser/Lines.php',
			'hook\\commit\\parser\\parser' => '/Commit/Parser/Parser.php',
			'hook\\commit\\parser\\properties' => '/Commit/Parser/Properties.php',
			'hook\\core\\arguments' => '/Core/Arguments.php',
			'hook\\core\\config' => '/Core/Config.php',
			'hook\\core\\error' => '/Core/Error.php',
			'hook\\core\\hook' => '/Core/Hook.php',
			'hook\\core\\log' => '/Core/Log.php',
			'hook\\core\\repository' => '/Core/Repository.php',
			'hook\\core\\svn' => '/Core/Svn.php',
			'hook\\core\\usage' => '/Core/Usage.php',
			'hook\\filter\\filter' => '/Filter/Filter.php',
			'hook\\filter\\objectfilter' => '/Filter/ObjectFilter.php',
			'hook\\listener\\abstractinfo' => '/Listener/AbstractInfo.php',
			'hook\\listener\\abstractobject' => '/Listener/AbstractObject.php',
			'hook\\listener\\infointerface' => '/Listener/InfoInterface.php',
			'hook\\listener\\listenerinterface' => '/Listener/ListenerInterface.php',
			'hook\\listener\\loader' => '/Listener/Loader.php',
			'hook\\listener\\objectinterface' => '/Listener/ObjectInterface.php'
		);
	} // if

	$sClassName = strtolower($sClass);

	if (isset($aClasses[$sClassName]))
	{
		require __DIR__ . $aClasses[$sClassName];
	} // if
} // function

// Register spl auto load.
spl_autoload_register('hfautoload');
