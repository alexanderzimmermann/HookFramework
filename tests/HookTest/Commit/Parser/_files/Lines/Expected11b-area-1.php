<?php
use Hook\Commit\Data\Diff\Diff;
use Hook\Commit\Data\Diff\Lines as DiffLines;

$oDiff = new Diff();

$oDiff->setOldStart(35);
$oDiff->setOldLength(10);

$oDiff->setNewStart(35);
$oDiff->setNewLength(10);

/* ==== */

$aRaw = array(
		 ' class WhiteFile',
		 ' {',
		 ' 	/**',
		 '-	 * A member var.',
		 '-	 * @var stdClass',
		 '+	 * List with generated random numbers.',
		 '+	 * @var array',
		 ' 	 */',
		 '-	private $oMember;',
		 '+	private $aNumbers = array();',
		 '',
		 ' 	/**',
		 ' 	 * Init.',
		);

$aNew = array(
		 38 => '	 * List with generated random numbers.',
		 39 => '	 * @var array',
		 41 => '	private $aNumbers = array();'
		);

$aOld = array(
		 38 => '	 * A member var.',
		 39 => '	 * @var stdClass',
		 41 => '	private $oMember;'
		);

$oExpected = new DiffLines;
$oExpected->setRawLines($aRaw);
$oExpected->setNewLines($aNew);
$oExpected->setOldLines($aOld);


$oExpected->setInfo($oDiff);

