<?php
use Hook\Commit\Diff\Diff;
use Hook\Commit\Diff\Lines as DiffLines;

$oDiff = new Diff();

$oDiff->setOldStart(32);
$oDiff->setOldLength(5);

$oDiff->setNewStart(34);
$oDiff->setNewLength(19);

/* ==== */

$aRaw = array(
    '  */',
    ' class FilteredFile',
    ' {',
    '+      /**',
    '+       * A member var.',
    '+       * @var stdClass',
    '+       */',
    '+      private $oMember;',
    '',
    '+      /**',
    '+       * Init.',
    '+       * @return void',
    '+       * @author Alexander Zimmermann <alex@azimmermann.com>',
    '+       */',
    '+      public function init()',
    '+      {',
    '+              $this->oMember = new stdClass();',
	'+      } // function',
	' } // class',
    '',
    '',
    ''
);

$aNew = array(
    37 => '      /**',
    38 => '       * A member var.',
    39 => '       * @var stdClass',
    40 => '       */',
    41 => '      private $oMember;',
    43 => '      /**',
    44 => '       * Init.',
    45 => '       * @return void',
    46 => '       * @author Alexander Zimmermann <alex@azimmermann.com>',
    47 => '       */',
    48 => '      public function init()',
    49 => '      {',
    50 => '              $this->oMember = new stdClass();',
	51 => '      } // function',
);

$aOld = array();

$oExpected = new DiffLines;
$oExpected->setRawLines($aRaw);
$oExpected->setNewLines($aNew);
$oExpected->setOldLines($aOld);


$oExpected->setInfo($oDiff);

