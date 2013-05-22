<?php
/**
 * Data in transaction.
 * @category   Core
 * @package    Parser
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter\Svn;

use Hook\Adapter\ControllerAbstract;
use Hook\Adapter\Svn\Parser\Parser as DiffParser;
use Hook\Commit\Info;
use Hook\Commit\Object;
use Hook\Commit\Data;

/**
 * Data in the transaction.
 * @category   Core
 * @package    Parser
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 3.0.0
 */
class Controller extends ControllerAbstract
{
    /**
     * Start to parse the commit..
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function parse()
    {
        $sTxn        = $this->oArguments->getTransaction();
        $iRev        = $this->oArguments->getRevision();
        $this->oData = new Data();

        // Only start has limited information.
        if ($this->oArguments->getMainType() === 'start') {
            $aInfo['txn']      = $sTxn;
            $aInfo['rev']      = $iRev;
            $aInfo['user']     = $this->oArguments->getUser();
            $aInfo['datetime'] = date('Y-m-d H:i:s', time());
            $aInfo['message']  = 'No Message in Start Hook';

            $this->oData->createInfo($aInfo);

            return;
        } // if

        // Parse info from commit.
        $this->parseInfo($this->oCommand->getInfo());

        $aDiffLines = $this->oCommand->getCommitDiff();

        // Parse array with the changed items.
        $this->parseItems($this->oCommand->getCommitChanged());

        $oParser = new DiffParser($this->aChangedItems, $aDiffLines);
        $oParser->parse();

        $this->createObjects($oParser->getProperties(), $oParser->getLines());
    }

    /**
     * Creating the data for the listener.
     * @param array $aProperties Properties of each item, if available.
     * @param array $aLines      Changed lines of each item, if available.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function createObjects(array $aProperties, array $aLines)
    {
        // Values for all items.
        $sTxn = $this->oArguments->getTransaction();
        $iRev = $this->oArguments->getRevision();

        $aObjects = array();
        foreach ($this->aChangedData as $iFor => $aData) {
            $aData['txn']   = $sTxn;
            $aData['rev']   = $iRev;
            $aData['props'] = array();
            $aData['lines'] = null;

            if (true === isset($aProperties[$iFor])) {
                $aData['props'] = $aProperties[$iFor];
            } // if

            if (true === isset($aLines[$iFor])) {
                $aData['lines'] = $aLines[$iFor];
            } // if


            $aObjects[] = $this->oData->createObject($aData);
        } // foreach

        // Set the commited objects for info listener.
        $this->oData->getInfo()->setObjects($aObjects);
    }

    /**
     * Parse info from the commit.
     * @param array $aData Commit Data info.
     * @return void
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function parseInfo(array $aData)
    {
        // Set defaults.
        $aInfo             = array();
        $aInfo['txn']      = $this->oArguments->getTransaction();
        $aInfo['rev']      = $this->oArguments->getRevision();
        $aInfo['user']     = '';
        $aInfo['datetime'] = '';
        $aInfo['message']  = '';

        // This elements in this order.
        $aProperties = array(
            'user', 'datetime', 'messagelength', 'message'
        );

        $iMax = count($aData);

        // Discard empty elements. Count could also be 0.
        if ($iMax > 4) {
            $iMax = 4;
        } // if

        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $sData = $aData[$iFor];
            if ($aProperties[$iFor] === 'message') {
                $aData[$iFor] = $this->parseMessage($sData);
            } // if

            $aInfo[$aProperties[$iFor]] = trim($aData[$iFor]);
        } // for

        $oInfo = new Info(
            $aInfo['txn'],
            $aInfo['rev'],
            $aInfo['user'],
            $aInfo['datetime'],
            $aInfo['message']
        );

        $this->oData->setInfo($oInfo);
    }

    /**
     * Parse message.
     * @param string $sMessage Commit Text.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    private function parseMessage($sMessage)
    {
        $aMatches = array();

        // Replace special signs in Format \\123.
        preg_match_all('/\?\\\\\\\\([0-9]+)/', $sMessage, $aMatches);

        $iMax = count($aMatches[0]);
        for ($iFor = 0; $iFor < $iMax; $iFor++) {
            $sChr     = $aMatches[0][$iFor];
            $iChr     = (int)$aMatches[1][$iFor];
            $sMessage = str_replace($sChr, chr($iChr), $sMessage);
        } // for

        return $sMessage;
    }
}
