.. _tutorials.quickstart.intro:

Quick start
===========

If all the :doc:`requirements <../ref/requirements>` fit to your system then you are 5 steps away from
a running system.

With git
--------

Assuming that you already cloned or init a `new git`_ repository on your local system.

.. code-block:: bash

   $ git clone https://github.com/alexanderzimmermann/HookFramework.git
   $ ./install.sh git ~/Projects/MyExampleGit

The output should look like

.. code-block:: bash

   * Copy hook files to target /home/alexander/projects/MyExampleGit/.git/hooks/
   * Replace default path /path/to/hookframework/ with /home/alexander/projects/HookFramework/ in /home/alexander/projects/MyExampleGit/.git/hooks/
   * Copy example listener /home/alexander/projects/HookFramework/Repositories/ExampleGit/* to Repositories/MyExampleGit
   * Change namespace in /home/alexander/projects/HookFramework/Repositories/MyExampleGit/ from namespace ExampleGit to namespace MyExampleGit

   Install completed


With subversion
---------------

Assuming that you on your subversion server or local system.

.. code-block:: bash

   $ sudo svnadmin create /var/svn/Example
   $ git clone git://github.com/alexanderzimmermann/HookFramework.git
   $ cd HookFramework
   $ sudo ./install.sh svn /var/svn/Example

The output should look like

.. code-block:: bash

   * Copy hook files to target /home/alexander/projects/MyExampleGit/.git/hooks/
   * Replace default path /path/to/hookframework/ with /home/alexander/projects/HookFramework/ in /home/alexander/projects/MyExampleGit/.git/hooks/
   * Copy example listener /home/alexander/projects/HookFramework/Repositories/ExampleGit/* to Repositories/MyExampleGit
   * Change namespace in /home/alexander/projects/HookFramework/Repositories/MyExampleGit/ from namespace ExampleGit to namespace MyExampleGit

   Install completed

Test the installation
---------------------
A little test is, just create a file named World.php.

.. code-block:: php

   <?php
   class World {}

Commit the file with no commit message. A dialog of your IDE should appear to tell that the commit failed and returns error lines.

.. code-block:: text

   svn: E165001: Commit failed (details follow):
   svn: E165001: Commit blocked by pre-commit hook (exit code 1) with output:
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   Strict Commit Message
   ================================================================================
   Please provide a comment for the commit
   The comment should be like:
   + If you add something.
   - If you delete something.
   * If you changed something.
   Commit Message
   ================================================================================
   Please provide a comment to this commit and use it as follows:
   + If something new is added.
   - If something is deleted.
   * If something is changed.
   trunk/Filter/NotFiltered/SyntaxError.php
   --------------------------------------------------------------------------------
   Style Guide
   ================================================================================
   FOUND 3 ERROR(S) AFFECTING 1 LINE(S)
   --------------------------------------------------------------------------------
   2 | ERROR | Each class must be in a namespace of at least one level (a
             | top-level vendor name)
   2 | ERROR | Opening brace of a class must be on the line after the definition
   2 | ERROR | Closing brace must be on a line by itself
   --------------------------------------------------------------------------------
   svn: E175002: MERGE of '/svn/Example/trunk/Filter/NotFiltered': 409 Conflict (http://localhost)


.. _`new git`: http://git-scm.com/book/en/Git-Basics-Getting-a-Git-Repository#Initializing-a-Repository-in-an-Existing-Directory