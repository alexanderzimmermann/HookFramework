<?php
/**
 * Usage-Objekt Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

require_once dirname(__FILE__) . '/../TestHelper.php';
require_once 'Core/Usage.php';

/**
 * Usage-Objekt Tests.
 * @category   Tests
 * @package    Main
 * @subpackage Core
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2011 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class UsageTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Testen für StartCommit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testUsageStartCommit()
	{
		$oUsage = new Usage('start', 'commit');

		// Erwarteter Text.
		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$USER     Benutzername des Commits' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n";
		$sExpected .= 'Beispiel: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $USER start-commit' . "\n";

		$this->assertEquals($sExpected, $oUsage->getUsage());
	} // function

	/**
	 * Testen fuer den PreCommit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testUsagePreCommit()
	{
		$oUsage = new Usage('pre', 'commit');

		// Erwarteter Text.
		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$TXN      Transaction (74-1)' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n";
		$sExpected .= 'Beispiel: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $TXN pre-commit' . "\n";

		$this->assertEquals($sExpected, $oUsage->getUsage());
	} // function

	/**
	 * Testen fuer den PreRevpropchange.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testUsagePreRevpropchange()
	{
		$oUsage = new Usage('pre', 'revprop-change');

		// Erwarteter Text.
		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$REV      Revisionsnummer die erstellt wird (74)' . "\n";
		$sExpected .= '$USER     Benutzername des Commits' . "\n";
		$sExpected .= '$PROPNAME Propertyname ' . "\n";
		$sExpected .= '$ACTION   Wird Property "A"dded, "M"odified ';
		$sExpected .= 'oder "D"eleted' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n";
		$sExpected .= 'Beispiel: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $REV $USER ';
		$sExpected .= '$SPROPNAME $ACTION pre-revprop-change' . "\n";

		$this->assertEquals($sExpected, $oUsage->getUsage());
	} // function

	/**
	 * Testen fuer den PreLock.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testUsagePreLock()
	{
		$oUsage = new Usage('pre', 'lock');

		// Erwarteter Text.
		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$PATH     Pfad im Repository das gesperrt ist.' . "\n";
		$sExpected .= '$USER     Benutzername des Commits' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n\n";
		$sExpected .= 'Beispiel: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $PATH $USER pre-lock' . "\n";

		$this->assertEquals($sExpected, $oUsage->getUsage());
	} // function

	/**
	 * Testen fuer den PreUnlock.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testUsagePreUnlock()
	{
		$oUsage = new Usage('pre', 'unlock');

		// Erwarteter Text.
		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$PATH     Pfad im Repository das gesperrt ist.' . "\n";
		$sExpected .= '$USER     Benutzername des Commits' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n";
		$sExpected .= 'Beispiel: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $PATH $USER pre-unlock' . "\n";

		$this->assertEquals($sExpected, $oUsage->getUsage());
	} // function

	/**
	 * Testen fuer den PostCommit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testUsagePostCommit()
	{
		$oUsage = new Usage('post', 'commit');

		// Erwarteter Text.
		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$REV      Revisionsnummer die erstellt wird (74)' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n";
		$sExpected .= 'Beispiel: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $REV post-commit' . "\n";

		$this->assertEquals($sExpected, $oUsage->getUsage());
	} // function

	/**
	 * Testen fuer den PostRevpropchange.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testUsagePostRevpropchange()
	{
		$oUsage = new Usage('post', 'revprop-change');

		// Erwarteter Text.
		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$REV      Revisionsnummer die erstellt ';
		$sExpected .= 'wird (74-1)' . "\n";
		$sExpected .= '$USER     Benutzername des Commits' . "\n";
		$sExpected .= '$PROPNAME Propertyname ' . "\n";
		$sExpected .= '$ACTION   Wird Property "A"dded, "M"odified ';
		$sExpected .= 'oder "D"eleted' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n";
		$sExpected .= 'Beispiel: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $REV $USER ';
		$sExpected .= '$SPROPNAME $ACTION post-revprop-change' . "\n";

		$this->assertEquals($sExpected, $oUsage->getUsage());
	} // function

	/**
	 * Testen fuer den PostLock.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testUsagePostLock()
	{
		$oUsage = new Usage('post', 'lock');

		// Erwarteter Text.
		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$USER     Benutzername des Commits' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n";
		$sExpected .= 'Beispiel: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $USER post-lock' . "\n";

		$this->assertEquals($sExpected, $oUsage->getUsage());
	} // function

	/**
	 * Testen fuer den PostUnlock.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testUsagePostUnlock()
	{
		$oUsage = new Usage('post', 'unlock');

		// Erwarteter Text.
		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$USER     Benutzername des Commits' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n";
		$sExpected .= 'Beispiel: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $USER post-unlock' . "\n";

		$this->assertEquals($sExpected, $oUsage->getUsage());
	} // function

	/**
	 * Testen für StartCommit.
	 * @return void
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function testUsageCommon()
	{
		$oUsage = new Usage('', '');

		// Erwarteter Text.
		$sExpected  = 'Aufruf mit folgenden Parametern und ';
		$sExpected .= 'Reihenfolge: ' . "\n";
		$sExpected .= "\n";
		$sExpected .= '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sExpected .= '$Params   Parameter je nach Hook Typ.' . "\n";
		$sExpected .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sExpected .= "\n";
		$sExpected .= 'Beispiele: ';
		$sExpected .= '/var/svn/hk/Hook $REPOS $TXN pre-commit' . "\n";
		$sExpected .= '/var/svn/hk/Hook $REPOS $REV post-commit' . "\n";

		$this->assertEquals($sExpected, $oUsage->getUsage());
	} // function
} // class
