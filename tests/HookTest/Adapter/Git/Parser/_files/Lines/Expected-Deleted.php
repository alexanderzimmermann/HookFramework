<?php
use Hook\Commit\Diff\Diff;
use Hook\Commit\Diff\Lines as DiffLines;

$oDiff = new Diff();

$oDiff->setOldStart(1);
$oDiff->setOldLength(69);

$oDiff->setNewStart(0);
$oDiff->setNewLength(0);

/* ==== */

$aRaw = array(
         '-#!/usr/bin/php',
         '-<?php',
         '-/**',
         '- * Executable for the hook tests.',
         '- * @category   Tests',
         '- * @package    Main',
         '- * @subpackage Core',
         '- * @author     Alexander Zimmermann <alex@zimmermann.com>',
         '- * @copyright  2008-2013 Alexander Zimmermann <alex@zimmermann.com>',
         '- * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License',
         '- * @version    PHP 5.4',
         '- * @link       http://www.azimmermann.com/',
         '- * @since      File available since Release 3.0.0',
         '- */',
         '-',
         '-/**',
         '-    The check for the params can be skipped, cause the script is only used in the test environment.',
         '-    Example: git diff --raw acdef098776554',
         '- */',
         '-',
         '-$aCommands = array(',
         '-    \'diff\', \'var\'',
         '-);',
         '-',
         '-if (false === isset($argv[1])) {',
         '-    exit;',
         '-}',
         '-',
         '-// Check commands',
         '-$sCommand = $argv[1];',
         '-if (in_array($sCommand, $aCommands) === false) {',
         '-',
         '-    exit;',
         '-}',
         '-// var_dump($argv);',
         '-',
         '-// DIFF Elements',
         '-if (strpos($argc[2], \'--\')) {',
         '-',
         '-    $sOption = $argv[2];',
         '-    $sTxn    = $argv[3];',
         '-} else {',
         '-    $sTxn = $argv[2];',
         '-}',
         '-',
         '-$sOption = $argv[2];',
         '-',
         '-',
         '-// Path to _files directory.',
         '-$sPath = str_replace(\'bin\', \'\', __DIR__);',
         '-',
         '-// Determine file.',
         '-$sCatFile = basename($sCatFile);',
         '-',
         '-// Prepare file for svn fake.',
         '-$sFile = $sPath . $sTxn . \'.txt\';',
         '-',
         '-// Send an error that the commit doesn\'t exists, when the file isn\'t available.',
         '-if (false === file_exists($sFile)) {',
         '-',
         '-    $sResult = \'fatal: ambiguous argument \\\'\' . $sTxn',
         '-               . \'\\\': unknown revision or path not in the working tree.\' . PHP_EOL',
         '-               . \'Use \\\'--\\\' to separate paths from revisions\' . PHP_EOL;',
         '-',
         '-    echo $sResult;',
         '-    exit(1);',
         '-}',
         '-',
         '-echo file_get_contents($sFile);',
         ''
        );

$aNew = array();

$aOld = array(
         1  => '#!/usr/bin/php',
         2  => '<?php',
         3  => '/**',
         4  => ' * Executable for the hook tests.',
         5  => ' * @category   Tests',
         6  => ' * @package    Main',
         7  => ' * @subpackage Core',
         8  => ' * @author     Alexander Zimmermann <alex@zimmermann.com>',
         9  => ' * @copyright  2008-2013 Alexander Zimmermann <alex@zimmermann.com>',
         10 => ' * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License',
         11 => ' * @version    PHP 5.4',
         12 => ' * @link       http://www.azimmermann.com/',
         13 => ' * @since      File available since Release 3.0.0',
         14 => ' */',
         15 => '',
         16 => '/**',
         17 => '    The check for the params can be skipped, cause the script is only used in the test environment.',
         18 => '    Example: git diff --raw acdef098776554',
         19 => ' */',
         20 => '',
         21 => '$aCommands = array(',
         22 => '    \'diff\', \'var\'',
         23 => ');',
         24 => '',
         25 => 'if (false === isset($argv[1])) {',
         26 => '    exit;',
         27 => '}',
         28 => '',
         29 => '// Check commands',
         30 => '$sCommand = $argv[1];',
         31 => 'if (in_array($sCommand, $aCommands) === false) {',
         32 => '',
         33 => '    exit;',
         34 => '}',
         35 => '// var_dump($argv);',
         36 => '',
         37 => '// DIFF Elements',
         38 => 'if (strpos($argc[2], \'--\')) {',
         39 => '',
         40 => '    $sOption = $argv[2];',
         41 => '    $sTxn    = $argv[3];',
         42 => '} else {',
         43 => '    $sTxn = $argv[2];',
         44 => '}',
         45 => '',
         46 => '$sOption = $argv[2];',
         47 => '',
         48 => '',
         49 => '// Path to _files directory.',
         50 => '$sPath = str_replace(\'bin\', \'\', __DIR__);',
         51 => '',
         52 => '// Determine file.',
         53 => '$sCatFile = basename($sCatFile);',
         54 => '',
         55 => '// Prepare file for svn fake.',
         56 => '$sFile = $sPath . $sTxn . \'.txt\';',
         57 => '',
         58 => '// Send an error that the commit doesn\'t exists, when the file isn\'t available.',
         59 => 'if (false === file_exists($sFile)) {',
         60 => '',
         61 => '    $sResult = \'fatal: ambiguous argument \\\'\' . $sTxn',
         62 => '               . \'\\\': unknown revision or path not in the working tree.\' . PHP_EOL',
         63 => '               . \'Use \\\'--\\\' to separate paths from revisions\' . PHP_EOL;',
         64 => '',
         65 => '    echo $sResult;',
         66 => '    exit(1);',
         67 => '}',
         68 => '',
         69 => 'echo file_get_contents($sFile);'
        );

$oExpected = new DiffLines;
$oExpected->setRawLines($aRaw);
$oExpected->setNewLines($aNew);
$oExpected->setOldLines($aOld);
$oExpected->setInfo($oDiff);
