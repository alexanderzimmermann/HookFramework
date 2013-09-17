.. _introduction.installation:

************
Installation
************

First, read the :ref:`requirements <requirements>`.

There are two ways to install or to activate the hookframework for the version control system.
The easiest way is to use the installation script. Or the manually installation.

Install the Hookframework via install script
============================================

The script just needs 2 parameters, which version control system you use and the path to the
repository you want to activate the hookframework to.

Assuming that you are in the directory of the hook framework, just type the following command:

.. code-block:: console

    $: [sudo] ./install.sh git /path/to/git/directory
    $: [sudo] ./install.sh svn /var/svn/REPOSITORY-NAME

That's it, now adjust the :ref:`listener <core.listener.introduction>` to your needs.


Install the Hookframework manually
==================================

Optional
~~~~~~~~

In order to run listener that use other software, you need to install this software they use. The example repository includes a listener that needs the [[PHP_CodeSniffer|http://pear.php.net/package/PHP_CodeSniffer]] installed.
Another listener mails the changes to a given address, so you need the mail function for php configured.

To run the unit tests PHPUnit 3.5 or higher is recommended.

Install and configure the hook framework
----------------------------------------

Be sure that the folders and files are executable by the PHP CLI. For example in ``/opt/hookframework/``.

Configuration file
~~~~~~~~~~~~~~~~~~

* Copy or rename the ``config-dist.ini`` file to ``config.ini``
* Adjust the path for the subversion executable (mostly ``/usr/bin/`` will fit) to get access to the subversion commands (svn, svnlook).
* Set the ``logfile`` path where you want to have the logging. Be sure the file is writeable to PHP.

Insert the hook framework in the subversion event hooks
-------------------------------------------------------

Every repository in subversion has a directory ``hooks``. In this directory are template files for each event, like pre-commit.tmpl, post-commit.tmpl and so on.
Rename the hook event you want to use the hook framework with by removing the ``.tmpl``.
Edit the event hook file and put the command instead of all other example code. Except the variable assigning ($REPOST, $TXN, etc.).
Under this assigning place the command ``/opt/hookframework/Hook "$REPOS" "$TXN" pre-commit >&2 || exit 1``

For the start-commit event use ``/opt/hookframework/Hook "$REPOS" "$USER" start-commit >&2 || exit 1``
For the post-commit event use ``/opt/hookframework/Hook "$REPOS" "$REV" post-commit >&2 || exit 1``

See the list of possible [[hook events for subversion]].

*Example for configuring the pre-commit file*

.. code-block:: console

	$: cp /opt/hookframework/Docs/svn-templates/pre-commit.tmpl /var/svn/YourSubversionRepository/hooks/pre-commit
	$: vim /var/svn/YourSubversionRepository/hooks/pre-commit


A sample file could look like this:

.. code-block:: bash

	#!/bin/sh
	# PRE-COMMIT HOOK
	#
	# The pre-commit hook is invoked before a Subversion txn is
	# committed.  Subversion runs this hook by invoking a program
	# (script, executable, binary, etc.) named 'pre-commit' (for which
	# this file is a template), with the following ordered arguments:
	#
	#   [1] REPOS-PATH   (the path to this repository)
	#   [2] TXN          (the name of the txn about to be committed)

	REPOS="$1"
	TXN="$2"

	/opt/hookframework/Hook "$REPOS" "$TXN" pre-commit >&2 || exit 1

	exit 0



.. See the :ref:`requirements appendix <requirements>` for a detailed list of requirements for Hook Framework.

Download `Hook Framework's Git repository`_ using a `Git`_ client. Hook Framework is open source software,
and the Git repository used for its development is publicly available on `GitHub`_. Consider using Git to get
Hook Framework if you want to contribute back to the framework, or need to upgrade your framework version more
often than releases occur.

Once you have a copy of Hook Framework available, the hook framework needs some configuration in
order to access all the informations needed for the :ref:`listener <core.listener.introduction>`.



.. _`Download the latest stable release.`: http://packages.zendframework.com/
.. _`Git`: http://git-scm.com/
.. _`GitHub`: http://github.com/
.. _`Hook Framework's Git repository`: https://github.com/alexanderzimmermann/hookframework
