.. _git.git.installation:

Installation
============

Requirements
------------

* See :ref:`requirements.php` requirements.
* Git is already installed on your client and or server.
* You already cloned or init a `new git`_ repository on your local system.


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

Assuming that you are in the directory of the hook framework, just type the following command:

.. code-block:: console

    $: [sudo] ./install.sh git /path/to/git/directory

The output should look like

.. code-block:: bash

   * Copy hook files to target /home/alexander/projects/MyExampleGit/.git/hooks/
   * Replace default path /path/to/hookframework/ with /home/alexander/projects/HookFramework/ in /home/alexander/projects/MyExampleGit/.git/hooks/
   * Copy example listener /home/alexander/projects/HookFramework/Repositories/MyExampleGit/* to Repositories/MyExampleGit
   * Change namespace in /home/alexander/projects/HookFramework/Repositories/MyExampleGit/ from namespace ExampleGit to namespace MyExampleGit

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


Install the hooks manually
==========================

Overview
--------
Every directory that is initialized for git (``git init``) has a directory ``.git/hooks``.
In this directory are template files for each event, like pre-commit.sample, commit-msg.sample
and so on.

Rename the hook event you want to use the hook framework with by removing the ``.sample`` extension.
Edit the hook file and put the following command instead of all other example code.

Under this assigning place the command ``/path/to/hookframework/Hook $repository $against client.pre-commit >&2 || exit 1``

For the pre-commit event use ``/path/to/hookframework/Hook $repository $against client.pre-commit >&2 || exit 1``
For the commit-msg event use ``/path/to/hookframework/Hook $repository $against $commitmsgfile client.commit-msg >&2 || exit 1``

See the list of possible `hook events for git`_.

Hooks
-----
For an easier start, just copy the needed hook file from the template folder and adjust the path in
the file.

.. code-block:: console

   $: cp /path/to/hookframework/Docs/templates/git/pre-commit /path/to/your/git/directory/.git/hooks/pre-commit
   $: vim /path/to/your/git/directory/.git/hooks/pre-commit
   $: chmod +x /path/to/your/git/directory.git/hooks/pre-commit

A file could look like this:

.. code-block:: bash

   #!/bin/sh
   #
   # The pre-commit hook is run first, before you even type in a commit message.
   # It’s used to inspect the snapshot that’s about to be committed, to see if
   # you’ve forgotten something, to make sure tests run, or to examine whatever
   # you need to inspect in the code. Exiting non-zero from this hook aborts the
   # commit, although you can bypass it with git commit --no-verify. You can do
   # things like check for code style (run lint or something equivalent), check
   # for trailing whitespace (the default hook does exactly that), or check for
   # appropriate documentation on new methods.

   if git rev-parse --verify HEAD >/dev/null 2>&1
   then
   	against=HEAD
   else
   	# Initial commit: diff against an empty tree object
   	against=4b825dc642cb6eb9a060e54bf8d69288fbee4904
   fi

   repository=$(git rev-parse --show-toplevel)

   /path/to/hookframework/Hook $repository $against client.pre-commit >&2 || exit 1

   exit 0


.. _`hook events for git`: http://git-scm.com/book/en/Customizing-Git-Git-Hooks
.. _`GitHub`: http://github.com/
.. _`latest version`: https://github.com/alexanderzimmermann/HookFramework/archive/master.zip
.. _`new git`: http://git-scm.com/book/en/Git-Basics-Getting-a-Git-Repository#Initializing-a-Repository-in-an-Existing-Directory