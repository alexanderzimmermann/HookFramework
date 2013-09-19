<?php
use Hook\Commit\Diff\Diff;
use Hook\Commit\Diff\Lines as DiffLines;

$oDiff = new Diff();

$oDiff->setOldStart(1);
$oDiff->setOldLength(14);

$oDiff->setNewStart(1);
$oDiff->setNewLength(14);

/* ==== */

$aRaw = array(

    ' <?php',
    '-class psr2',
    '+class Psr2',
    ' {',
    '- var $file;',
    '+ protected $file;',
    '',
    '  function construct()',
    '  {',
    '-   $this->file = \\\'\\\';',
    '+   $this->file;',
    '  }',
    '',
    '- function loadFile()',
    '+ public function loadFile()',
    '  {',
    '    // Load yourself.',
    '   $this->file = file(__FILE__);',
    '',
    ''
);

$aNew = array(
    2  => 'class Psr2',
    4  => ' protected $file;',
    8  => '   $this->file;',
    11 => ' public function loadFile()'
);

$aOld = array(
    2  => 'class psr2',
    4  => ' var $file;',
    8  => '   $this->file = \\\'\\\';',
    11 => ' function loadFile()'
);

$oExpected = new DiffLines;
$oExpected->setRawLines($aRaw);
$oExpected->setNewLines($aNew);
$oExpected->setOldLines($aOld);


$oExpected->setInfo($oDiff);
