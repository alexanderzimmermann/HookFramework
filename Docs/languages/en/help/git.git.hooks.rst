.. _git.git.hooks:

Hooks
=====

In git, the hooks are separated in client and server hooks.
Hooks are invoked every time a commit into the subversion repository is checked in. Hooks simply
are shell scripts. For safety all paths and system variables are not available. The hook scripts
are stored in the ``hooks`` directory in every git directory you've created.


.. include:: git.git.hooks.table.txt

The client pre-commit hook is the best place to check several things, like syntax checks,
before the code is stored in the repository.


.. note::

   The code should never been changed before it is commit.


.. note::

   For more information how hooks are invoked from subversion read the `online documentation`_ and
   the chapter to `customize git hooks`_.

Main Hooks
----------

The hook framework differs between the client and server hooks, pre and post. That is also the order in
that the hooks and its sub name are invoked. Also this are the main directories for the
:ref:`listener <core.listener.introduction>`.


.. _`online documentation`: http://git-scm.com/
.. _`customize git hooks`: http://git-scm.com/book/en/Customizing-Git-Git-Hooks