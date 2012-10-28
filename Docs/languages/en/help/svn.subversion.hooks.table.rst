.. _svn.subversion.hooks.table-1:

.. table:: Subversion Hooks list

   +-----------------------------+----------------------------------------------------------------------------+
   |Hooks                        |Time of invoke                                                              |
   +=============================+============================================================================+
   |start-commit                 |The first hook that is called.                                              |
   +-----------------------------+----------------------------------------------------------------------------+
   |pre-commit                   |This hook is called, when files and directories are commited, but before    |
   |                             |they are stored.                                                            |
   +-----------------------------+----------------------------------------------------------------------------+
   |pre-lock                     |This hook is only called, when a file is locked, but before it is           |
   |                             |definitely locked.                                                          |
   +-----------------------------+----------------------------------------------------------------------------+
   |pre-unlock                   |This hook is only called, when a file is unlocked, but before it is         |
   |                             |finally locked.                                                             |
   +-----------------------------+----------------------------------------------------------------------------+
   |pre-revprop-change           |This hook is only called, when properties of an object are changed, but     |
   |                             |before it is finally stored.                                                |
   +-----------------------------+----------------------------------------------------------------------------+
   |post-commit                  |This hook is called, when files and directories are commited, after         |
   |                             |the data is stored.                                                         |
   +-----------------------------+----------------------------------------------------------------------------+
   |post-lock                    |This hook is only called, when a file is locked, but after the              |
   |                             |lock is done.                                                               |
   +-----------------------------+----------------------------------------------------------------------------+
   |post-unlock                  |This hook is only called, when a file is unlocked, but after it is locked.  |
   +-----------------------------+----------------------------------------------------------------------------+
   |post-revprop-change          |This hook is only called, when properties of an object are changed, but     |
   |                             |after the data is stored.                                                   |
   +-----------------------------+----------------------------------------------------------------------------+
