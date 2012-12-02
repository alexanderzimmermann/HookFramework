<?php
use Hook\Commit\Data\Diff\Diff;
use Hook\Commit\Data\Diff\Lines as DiffLines;

$oDiff = new Diff();

$oDiff->setOldStart(47);
$oDiff->setOldLength(20);

$oDiff->setNewStart(47);
$oDiff->setNewLength(29);

/* ==== */

$aRaw = array(
		 ' 	 */',
		 ' 	public function init()',
		 ' 	{',
		 '-		$this->oMember = new stdClass();',
		 ' 	} // function',
		 '',
		 ' 	/**',
		 '+	 * Get the random numbers.',
		 '+	 * @author Alexander Zimmermann <alex@azimmermann.com>',
		 '+	 */',
		 '+	public function getRandomNumbers()',
		 '+	{',
		 '+		return $this->aNumbers;',
		 '+	} // function',
		 '+',
		 '+	/**',
		 ' 	 * Create some random numbers.',
		 ' 	 * @author Alexander Zimmermann <alex@azimmermann.com>',
		 ' 	 */',
		 ' 	protected function createRandomNumbers()',
		 ' 	{',
		 '-		$iMax     = 10;',
		 '-		$aNumbers = array();',
		 '+		$iMax           = 10;',
		 '+		$this->aNumbers = array();',
		 '+',
		 ' 		for ($iFor = 0; $iFor < $iMax; $iFor++)',
		 ' 		{',
		 '-			$aNumbers[] = rand(5, 20);',
		 '+			$this->aNumbers[] = rand(5, 20);',
		 ' 		} // for',
		 ' 	} // function',
		 ' } // class',
		 '',
		 '',
		 ''
		);

$aNew = array(
		 53 => '	 * Get the random numbers.',
		 54 => '	 * @author Alexander Zimmermann <alex@azimmermann.com>',
		 55 => '	 */',
		 56 => '	public function getRandomNumbers()',
		 57 => '	{',
		 58 => '		return $this->aNumbers;',
		 59 => '	} // function',
		 60 => '',
		 61 => '	/**',
		 67 => '		$iMax           = 10;',
		 68 => '		$this->aNumbers = array();',
		 69 => '',
		 72 => '			$this->aNumbers[] = rand(5, 20);'
		);

$aOld = array(
		 50 => '		$this->oMember = new stdClass();',
		 59 => '		$iMax     = 10;',
		 60 => '		$aNumbers = array();',
		 63 => '			$aNumbers[] = rand(5, 20);'
		);

$oExpected = new DiffLines;
$oExpected->setRawLines($aRaw);
$oExpected->setNewLines($aNew);
$oExpected->setOldLines($aOld);


$oExpected->setInfo($oDiff);

