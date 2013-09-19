<?php
use Hook\Commit\Diff\Diff;
use Hook\Commit\Diff\Lines as DiffLines;

$oDiff = new Diff();

$oDiff->setOldStart(17);
$oDiff->setOldLength(6);

$oDiff->setNewStart(17);
$oDiff->setNewLength(8);

/* ==== */

$aRaw = array(
    '',
    ' namespace Filter\\\NotFiltered;',
    '',
    '+use stdClass;',
    '+',
    ' /**',
    '  * This is a filtered simple file.',
    '  *'
);

$aNew = array(
    20 => 'use stdClass;',
    21 => ''
);

$aOld = array();

$oExpected = new DiffLines;
$oExpected->setRawLines($aRaw);
$oExpected->setNewLines($aNew);
$oExpected->setOldLines($aOld);


$oExpected->setInfo($oDiff);
