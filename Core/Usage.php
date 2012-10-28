<?php
/**
 * Usage class for a little help output.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Core;

/**
 * Usage class for a little help output.
 * @category   Core
 * @package    Main
 * @subpackage Main
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2012 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.1
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class Usage
{
	/**
	 * Usage Methode die aufgerufen werden soll.
	 * @var string
	 */
	private $sUsageMethod;

	/**
	 * Constructor.
	 * @param string $sMainType Haupttyp Hook (start, pre, post).
	 * @param string $sSubType  Subtyp (commit, lock, revprop-change).
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function __construct($sMainType, $sSubType)
	{
		// Durch den Argumenten Check wird davon ausgegangen das der Parameter
		// schon korrekt sein wird.
		$this->sUsageMethod  = 'get';
		$this->sUsageMethod .= ucfirst($sMainType);
		$this->sUsageMethod .= ucfirst(str_replace('-', '', $sSubType));
	} // function

	/**
	 * Usage Text zurueck geben.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	public function getUsage()
	{
		$sMethod = $this->sUsageMethod;

		// Kopf.
		$sUsage  = 'Aufruf mit folgenden Parametern und Reihenfolge: ' . "\n";
		$sUsage .= "\n";

		$aMethods = get_class_methods(get_class($this));

		if (in_array($sMethod, $aMethods) === true)
		{
			return $sUsage . $this->$sMethod();
		} // if

		return $sUsage . $this->getCommonUsage();
	} // function

	/**
	 * Start Commit Hook Usage.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getStartCommit()
	{
		/**
			Parameter die Subversion im Hook bereit stellt:
			[1] REPOS-PATH  (the path to this repository)
			[2] USER        (the authenticated user attempting to commit)
		*/

		$sUsage  = '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sUsage .= '$USER     Benutzername des Commits' . "\n";
		$sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sUsage .= "\n";
		$sUsage .= 'Beispiel: ';
		$sUsage .= '/var/svn/hk/Hook $REPOS $USER start-commit' . "\n";

		return $sUsage;
	} // function

	/**
	 * Pre Commit Hook Usage.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getPreCommit()
	{
		/**
			Parameter die Subversion im Hook bereit stellt:
			[1] REPOS-PATH  (the path to this repository)
			[2] TXN         (the name of the txn about to be committed)
		*/

		$sUsage  = '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sUsage .= '$TXN      Transaction (74-1)' . "\n";
		$sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sUsage .= "\n";
		$sUsage .= 'Beispiel: ';
		$sUsage .= '/var/svn/hk/Hook $REPOS $TXN pre-commit' . "\n";

		return $sUsage;
	} // function

	/**
	 * Pre Revpropchange Hook Usage.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getPreRevpropchange()
	{
		/**
			Parameter die Subversion im Hook bereit stellt:
			[1] REPOS-PATH  (the path to this repository)
			[2] REV         (the revision being tweaked)
			[3] USER        (the username of the person tweaking the property)
			[4] PROPNAME    (the property being set on the revision)
			[5] ACTION      (the property is being 'A'dded, 'M'odified,
			                 or 'D'eleted)

			[STDIN] PROPVAL  ** the new property value is passed via STDIN.
		*/

		$sUsage  = '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sUsage .= '$REV      Revisionsnummer die erstellt wird (74)' . "\n";
		$sUsage .= '$USER     Benutzername des Commits' . "\n";
		$sUsage .= '$PROPNAME Propertyname ' . "\n";
		$sUsage .= '$ACTION   Wird Property "A"dded, "M"odified oder ';
		$sUsage .= '"D"eleted' . "\n";
		$sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sUsage .= "\n";
		$sUsage .= 'Beispiel: ';
		$sUsage .= '/var/svn/hk/Hook $REPOS $REV $USER ';
		$sUsage .= '$SPROPNAME $ACTION pre-revprop-change' . "\n";

		return $sUsage;
	} // function

	/**
	 * Pre Lock Hook Usage.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getPreLock()
	{
		/**
			Parameter die Subversion im Hook bereit stellt:
			[1] REPOS-PATH   (the path to this repository)
			[2] PATH         (the path in the repository about to be locked)
			[3] USER         (the user creating the lock)
		*/

		$sUsage  = '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sUsage .= '$PATH     Pfad im Repository das gesperrt ist.' . "\n";
		$sUsage .= '$USER     Benutzername des Commits' . "\n";
		$sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sUsage .= "\n\n";
		$sUsage .= 'Beispiel: ';
		$sUsage .= '/var/svn/hk/Hook $REPOS $PATH $USER pre-lock' . "\n";

		return $sUsage;
	} // function

	/**
	 * Pre Unlock Hook Usage.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getPreUnlock()
	{
		/**
			Parameter die Subversion im Hook bereit stellt:
			[1] REPOS-PATH   (the path to this repository)
			[2] PATH         (the path in the repository about to be locked)
			[3] USER         (the user creating the lock)
		*/

		$sUsage  = '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sUsage .= '$PATH     Pfad im Repository das gesperrt ist.' . "\n";
		$sUsage .= '$USER     Benutzername des Commits' . "\n";
		$sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sUsage .= "\n";
		$sUsage .= 'Beispiel: ';
		$sUsage .= '/var/svn/hk/Hook $REPOS $PATH $USER pre-unlock' . "\n";

		return $sUsage;
	} // function

	/**
	 * Post Commit Hook Usage.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getPostCommit()
	{
		/**
			Parameter die Subversion im Hook bereit stellt:
			[1] REPOS-PATH   (the path to this repository)
			[2] REV          (the number of the revision just committed)
		*/

		$sUsage  = '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sUsage .= '$REV      Revisionsnummer die erstellt wird (74)' . "\n";
		$sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sUsage .= "\n";
		$sUsage .= 'Beispiel: ';
		$sUsage .= '/var/svn/hk/Hook $REPOS $REV post-commit' . "\n";

		return $sUsage;
	} // function

	/**
	 * Post Revpropchange Hook Usage.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getPostRevpropchange()
	{
		/**
			Parameter die Subversion im Hook bereit stellt:
			[1] REPOS-PATH  (the path to this repository)
			[2] REV         (the revision being tweaked)
			[3] USER        (the username of the person tweaking the property)
			[4] PROPNAME    (the property being set on the revision)
			[5] ACTION      (the property is being 'A'dded, 'M'odified,
			                 or 'D'eleted)

			[STDIN] PROPVAL  ** the new property value is passed via STDIN.
		*/

		$sUsage  = '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sUsage .= '$REV      Revisionsnummer die erstellt wird (74-1)' . "\n";
		$sUsage .= '$USER     Benutzername des Commits' . "\n";
		$sUsage .= '$PROPNAME Propertyname ' . "\n";
		$sUsage .= '$ACTION   Wird Property "A"dded, "M"odified oder ';
		$sUsage .= '"D"eleted' . "\n";
		$sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sUsage .= "\n";
		$sUsage .= 'Beispiel: ';
		$sUsage .= '/var/svn/hk/Hook $REPOS $REV $USER ';
		$sUsage .= '$SPROPNAME $ACTION post-revprop-change' . "\n";

		return $sUsage;
	} // function

	/**
	 * Post Lock Hook Usage.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getPostLock()
	{
		/**
			Parameter die Subversion im Hook bereit stellt:
			[1] REPOS-PATH   (the path to this repository)
			[2] USER         (the user who created the lock)
		*/

		$sUsage  = '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sUsage .= '$USER     Benutzername des Commits' . "\n";
		$sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sUsage .= "\n";
		$sUsage .= 'Beispiel: ';
		$sUsage .= '/var/svn/hk/Hook $REPOS $USER post-lock' . "\n";

		return $sUsage;
	} // function

	/**
	 * Post Unlock Hook Usage.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getPostUnlock()
	{
		/**
			Parameter die Subversion im Hook bereit stellt:
			[1] REPOS-PATH   (the path to this repository)
			[2] USER         (the user who destroyed the lock)
		*/

		$sUsage  = '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sUsage .= '$USER     Benutzername des Commits' . "\n";
		$sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sUsage .= "\n";
		$sUsage .= 'Beispiel: ';
		$sUsage .= '/var/svn/hk/Hook $REPOS $USER post-unlock' . "\n";

		return $sUsage;
	} // function

	/**
	 * Standard Usage, wenn Haupt und Subtyp nicht korrekt waren.
	 * @return string
	 * @author Alexander Zimmermann <alex@azimmermann.com>
	 */
	private function getCommonUsage()
	{
		$sUsage  = '$REPOS    Repository Pfad (/var/svn/project)' . "\n";
		$sUsage .= '$Params   Parameter je nach Hook Typ.' . "\n";
		$sUsage .= 'Hook      start-commit, pre-commit, post-commit' . "\n";
		$sUsage .= "\n";
		$sUsage .= 'Beispiele: ';
		$sUsage .= '/var/svn/hk/Hook $REPOS $TXN pre-commit' . "\n";
		$sUsage .= '/var/svn/hk/Hook $REPOS $REV post-commit' . "\n";

		return $sUsage;
	} // function
} // class
