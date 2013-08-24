<?php
use Hook\Commit\Diff\Diff;
use Hook\Commit\Diff\Lines as DiffLines;

$oDiff = new Diff();

$oDiff->setOldStart(43);
$oDiff->setOldLength(6);

$oDiff->setNewStart(43);
$oDiff->setNewLength(12);

/* ==== */

$aRaw = array(
         '     protected $sCommand = \'\';',
         '',
         '     /**',
         '+     * Error during command execution.',
         '+     * @var boolean',
         '+     */',
         '+    protected $bError;',
         '+',
         '+    /**',
         '      * Constructor.',
         '      * @param string $sBinPath Path to the subversion executable.',
         '      * @author Alexander Zimmermann <alex@azimmermann.com>',
        );

$aNew = array(
         46 => '     * Error during command execution.',
         47 => '     * @var boolean',
         48 => '     */',
         49 => '    protected $bError;',
         50 => '',
         51 => '    /**',
        );

$aOld = array();

$oExpected1 = new DiffLines;
$oExpected1->setRawLines($aRaw);
$oExpected1->setNewLines($aNew);
$oExpected1->setOldLines($aOld);
$oExpected1->setInfo($oDiff);

/* ==== */

$oDiff = new Diff();

$oDiff->setOldStart(54);
$oDiff->setOldLength(7);

$oDiff->setNewStart(60);
$oDiff->setNewLength(7);

/* ==== */

$aRaw = array(
         '',
         '     /**',
         '      * Execute the svn command line.',
         '-     * @param string $sCommand SVN Command.',
         '+     * @param string $sCommand VCS Command.',
         '      * @return array',
         '      * @author Alexander Zimmermann <alex@azimmermann.com>',
         '      */',
        );

$aNew = array(
         63 => '     * @param string $sCommand VCS Command.',
        );

$aOld = array(
         57 => '     * @param string $sCommand SVN Command.'
        );

$oExpected2 = new DiffLines;
$oExpected2->setRawLines($aRaw);
$oExpected2->setNewLines($aNew);
$oExpected2->setOldLines($aOld);
$oExpected2->setInfo($oDiff);

/* ==== */

$oDiff = new Diff();

$oDiff->setOldStart(65);
$oDiff->setOldLength(8);

$oDiff->setNewStart(71);
$oDiff->setNewLength(28);

/* ==== */

$aRaw = array(
         '',
         '         exec($sCommand, $aData);',
         '',
         '+        // Check the result for errors.',
         '+        $aData = $this->checkResult($aData);',
         '+',
         '         $oLog->writeLog(Log::HF_VARDUMP, \'result lines\', $aData);',
         '',
         '         return $aData;',
         '     }',
         '+',
         '+    /**',
         '+     * Check the result for errors.',
         '+     * @param array $aData Data from exec command.',
         '+     * @author Alexander Zimmermann <alex@azimmermann.com>',
         '+     */',
         '+    abstract protected function checkResult(array $aData);',
         '+',
         '+    /**',
         '+     * Indicates whether errors have occurred.',
         '+     * @return boolean',
         '+     * @author Alexander Zimmermann <alex@azimmermann.com>',
         '+     */',
         '+    public function hasError()',
         '+    {',
         '+        return $this->bError;',
         '+    }',
         ' }',
         ''
        );

$aNew = array(
         74 => '        // Check the result for errors.',
         75 => '        $aData = $this->checkResult($aData);',
         76 => '',
         81 => '',
         82 => '    /**',
         83 => '     * Check the result for errors.',
         84 => '     * @param array $aData Data from exec command.',
         85 => '     * @author Alexander Zimmermann <alex@azimmermann.com>',
         86 => '     */',
         87 => '    abstract protected function checkResult(array $aData);',
         88 => '',
         89 => '    /**',
         90 => '     * Indicates whether errors have occurred.',
         91 => '     * @return boolean',
         92 => '     * @author Alexander Zimmermann <alex@azimmermann.com>',
         93 => '     */',
         94 => '    public function hasError()',
         95 => '    {',
         96 => '        return $this->bError;',
         97 => '    }',
        );

$aOld = array(
        );

$oExpected3 = new DiffLines;
$oExpected3->setRawLines($aRaw);
$oExpected3->setNewLines($aNew);
$oExpected3->setOldLines($aOld);
$oExpected3->setInfo($oDiff);

/* ==== */

