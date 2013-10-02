.. _svn.subversion.installation:

Installation
============

Requirements
------------

* See :ref:`requirements.php` requirements.
* Subversion is already installed on your system.
* You have already created a subversion repository (`svnadmin create /var/svn/Example`).


Download the Hookframework
==========================

If you have a `GitHub`_ account, fork the repository and clone the repository.

.. code-block:: console

   $: git clone https://github.com/alexanderzimmermann/HookFramework.git

This will create a directory HookFramework in the actual directory.

Otherwise download the `latest version`_.


Install the hooks via install script
====================================

The script just needs 2 parameters, which version control system you use and the path to the
repository you want to activate the hookframework to.

Assuming that you are in the directory of the hook framework and that you are on your subversion
server, just type the following command:

.. code-block:: bash

   $ cd HookFramework
   $ sudo ./install.sh svn /var/svn/MySvnRepo

The output should look like

.. code-block:: bash

   * Copy hook files to target /var/svn/MySvnRepo/hooks/
   * Replace default path /path/to/hookframework/ with /var/svn/MySvnRepo/HookFramework/ in /var/svn/MySvnRepoMySvnRepo/hooks/
   * Copy example listener /opt/HookFramework/Repositories/ExampleSvn/* to /opt/HookFramework/Repositories/MySvnRepo
   * Change namespace in /opt/HookFramework/Repositories/MySvnRepo/ from namespace ExampleSvn to namespace MySvnRepo

   Install completed

That's it, now adjust the :ref:`listener <core.listener.introduction>` to your needs.


Test the installation
=====================

A little test is, just create a file named World.php.

.. code-block:: php

   <?php
   class World {}

Commit the file with no commit message. A dialog of your IDE should appear to tell that the commit failed and returns error lines.

.. code-block:: text

   svn: E165001: Commit failed (details follow):
   svn: E165001: Commit blocked by pre-commit hook (exit code 1) with output:
   /++++++++++++++++++++++++++++++
    + HOOK: Strict Commit Message
   Please provide a comment for the commit
   The comment should be like:
   + If you add something.
   - If you delete something.
   * If you changed something.

   /++++++++++++++++++++++++++++++
    + HOOK: Commit Message
   Please provide a comment to this commit and use it as follows:
   + If something new is added.
   - If something is deleted.
   * If something is changed.
   trunk/Filter/NotFiltered/SyntaxError.php

   /++++++++++++++++++++++++++++++
    + Style Guide
   FOUND 3 ERROR(S) AFFECTING 1 LINE(S)
   --------------------------------------------------------------------------------
   2 | ERROR | Each class must be in a namespace of at least one level (a
             | top-level vendor name)
   2 | ERROR | Opening brace of a class must be on the line after the definition
   2 | ERROR | Closing brace must be on a line by itself
   --------------------------------------------------------------------------------
   svn: E175002: MERGE of '/svn/Example/trunk/Filter/NotFiltered': 409 Conflict (http://localhost)


Install the hooks manually
==========================

Overview
--------
Every repository in subversion has a directory ``hooks``. In this directory are template files for each event, like pre-commit.tmpl, post-commit.tmpl and so on.
Rename the hook event you want to use the hook framework with by removing the ``.tmpl``.
Edit the event hook file and put the command instead of all other example code. Except the variable assigning ($REPOST, $TXN, etc.).
Under this assigning place the command ``/opt/hookframework/Hook "$REPOS" "$TXN" pre-commit >&2 || exit 1``

For the start-commit event use ``/opt/hookframework/Hook "$REPOS" "$USER" start-commit >&2 || exit 1``
For the post-commit event use ``/opt/hookframework/Hook "$REPOS" "$REV" post-commit >&2 || exit 1``

See the list of possible `hook events for subversion`_.

Hooks
-----
For an easier start, just copy the needed hook file from the template folder and adjust the path in
the file.

.. code-block:: console

   $: cp /opt/hookframework/Docs/templates/svn/pre-commit /var/svn/YourSubversionRepository/hooks/pre-commit
   $: vim /var/svn/YourSubversionRepository/hooks/pre-commit
   $: chmod +x /var/svn/YourSubversionRepository/hooks/pre-commit


A file could look like this:

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



.. _`online documentation`: http://svnbook.red-bean.com/
.. _`create hooks`: http://svnbook.red-bean.com/nightly/en/svn.reposadmin.create.html#svn.reposadmin.create.hooks
.. _hook events for subversion: http://svnbook.red-bean.com/nightly/en/svn.ref.reposhooks.html
.. _GitHub: http://github.com/
.. _`latest version`: https://github.com/alexanderzimmermann/HookFramework/archive/master.zip