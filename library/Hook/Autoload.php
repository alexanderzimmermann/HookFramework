<?php
/**
 * Autoload function.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
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

    if ($aClasses === null) {
        $aClasses = array(
            'hook\\adapter\\argumentsinterface' => '/Adapter/ArgumentsInterface.php',
   'hook\\adapter\\changedabstract' => '/Adapter/ChangedAbstract.php',
   'hook\\adapter\\changedinterface' => '/Adapter/ChangedInterface.php',
   'hook\\adapter\\commandabstract' => '/Adapter/CommandAbstract.php',
   'hook\\adapter\\commandinterface' => '/Adapter/CommandInterface.php',
   'hook\\adapter\\controllerabstract' => '/Adapter/ControllerAbstract.php',
   'hook\\adapter\\git\\arguments' => '/Adapter/Git/Arguments.php',
   'hook\\adapter\\git\\command' => '/Adapter/Git/Command.php',
   'hook\\adapter\\git\\controller' => '/Adapter/Git/Controller.php',
   'hook\\adapter\\git\\parser\\changed' => '/Adapter/Git/Parser/Changed.php',
   'hook\\adapter\\git\\usage' => '/Adapter/Git/Usage.php',
   'hook\\adapter\\loaderabstract' => '/Adapter/LoaderAbstract.php',
   'hook\\adapter\\svn\\arguments' => '/Adapter/Svn/Arguments.php',
   'hook\\adapter\\svn\\argumentsabstract' => '/Adapter/ArgumentsAbstract.php',
   'hook\\adapter\\svn\\command' => '/Adapter/Svn/Command.php',
   'hook\\adapter\\svn\\controller' => '/Adapter/Svn/Controller.php',
   'hook\\adapter\\svn\\loader' => '/Adapter/Svn/Loader.php',
   'hook\\adapter\\svn\\parser\\changed' => '/Adapter/Svn/Parser/Changed.php',
   'hook\\adapter\\svn\\parser\\info' => '/Adapter/Svn/Parser/Info.php',
   'hook\\adapter\\svn\\parser\\lines' => '/Adapter/Svn/Parser/Lines.php',
   'hook\\adapter\\svn\\parser\\parser' => '/Adapter/Svn/Parser/Parser.php',
   'hook\\adapter\\svn\\parser\\properties' => '/Adapter/Svn/Parser/Properties.php',
   'hook\\adapter\\svn\\usage' => '/Adapter/Svn/Usage.php',
   'hook\\commit\\base' => '/Commit/Base.php',
   'hook\\commit\\data' => '/Commit/Data.php',
   'hook\\commit\\diff\\diff' => '/Commit/Diff/Diff.php',
   'hook\\commit\\diff\\lines' => '/Commit/Diff/Lines.php',
   'hook\\commit\\diff\\property' => '/Commit/Diff/Property.php',
   'hook\\commit\\info' => '/Commit/Info.php',
   'hook\\commit\\object' => '/Commit/Object.php',
   'hook\\core\\config' => '/Core/Config.php',
   'hook\\core\\error' => '/Core/Error.php',
   'hook\\core\\file' => '/Core/File.php',
   'hook\\core\\hook' => '/Core/Hook.php',
   'hook\\core\\log' => '/Core/Log.php',
   'hook\\core\\response' => '/Core/Response.php',
   'hook\\filter\\filter' => '/Filter/Filter.php',
   'hook\\filter\\objectfilter' => '/Filter/ObjectFilter.php',
   'hook\\listener\\abstractinfo' => '/Listener/AbstractInfo.php',
   'hook\\listener\\abstractobject' => '/Listener/AbstractObject.php',
   'hook\\listener\\infointerface' => '/Listener/InfoInterface.php',
   'hook\\listener\\listenerinterface' => '/Listener/ListenerInterface.php',
   'hook\\listener\\objectinterface' => '/Listener/ObjectInterface.php'
        );
    }

    $sClassName = strtolower($sClass);

    if (isset($aClasses[$sClassName])) {
        require __DIR__ . $aClasses[$sClassName];
    }
}

// Register spl auto load.
spl_autoload_register('hfautoload');
