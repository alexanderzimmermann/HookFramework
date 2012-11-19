.. _tutorials.quickstart.intro:

Quick start
===========

If all the :doc:`requirements <../ref/requirements>` fit to your system then you are 4 steps away from
a running system.

.. code-block:: text

   $ sudo svnadmin create /var/svn/Example

   $ git clone git://github.com/alexanderzimmermann/HookFramework.git

   $ cd HookFramework

   $ sudo ./install.sh /var/svn/Example

The output should look like

.. code-block:: text

   Copy hook files to target /var/svn/Install/hooks

   Replace default path /path/to/hookframework/ with /home/YOURUSERNAME/Projekte/Test/HookFramework/

   Copy example listener /home/YOURUSERNAME/Projekte/Test/HookFramework/ to Repositories/Install

   Done.



Test the installation
---------------------
A little test is, just change a file and commit it with no message. A dialog of your IDE should
appear to tell that the commit failed and returns error lines.

Example from PhpStorm.

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
   FOUND 18 ERROR(S) AND 1 WARNING(S) AFFECTING 5 LINE(S)
   --------------------------------------------------------------------------------
     6 | WARNING | PHP version not specified
     6 | ERROR   | Missing @category tag in file comment
     6 | ERROR   | Missing @package tag in file comment
     6 | ERROR   | Missing @author tag in file comment
     6 | ERROR   | Missing @license tag in file comment
     6 | ERROR   | Missing @link tag in file comment
    12 | ERROR   | Missing @category tag in class comment
    12 | ERROR   | Missing @package tag in class comment
    12 | ERROR   | Missing @author tag in class comment
    12 | ERROR   | Missing @license tag in class comment
    12 | ERROR   | Missing @link tag in class comment
    20 | ERROR   | Missing @return tag in function comment
    21 | ERROR   | Public method name "SyntaxError::_construct" must not be
       |         | prefixed with an underscore
    23 | ERROR   | Constants must be uppercase; expected THIS but found This
    23 | ERROR   | Constants must be uppercase; expected WILL but found will
    23 | ERROR   | Constants must be uppercase; expected CAUSE but found cause
    23 | ERROR   | Constants must be uppercase; expected A but found a
    23 | ERROR   | Constants must be uppercase; expected SYNTAX but found syntax
    23 | ERROR   | Constants must be uppercase; expected ERROR but found error
   --------------------------------------------------------------------------------
   Time: 0 seconds, Memory: 3.25Mb
   Syntax check
   ================================================================================
   Errors parsing /tmp/10-i-trunk_Filter_NotFiltered_SyntaxError.php
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   svn: E175002: MERGE of '/svn/Example/trunk/Filter/NotFiltered': 409 Conflict (http://localhost)