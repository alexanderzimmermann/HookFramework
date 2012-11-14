.. _svn._subversion.hooks:

Subversion Hooks
================

Hooks are invoked every time a commit into the subversion repository is checked in. Hooks simply
are shell scripts. For safety all paths and system variables are not available. The hook scripts
are stored in the ``hooks`` directory in every repository.

By default there are seven hook scripts available, but are deactivated (extension .template). In
the following table is explained when which hook script is invoked.


.. include:: svn.subversion.hooks.table.rst

The start and pre commit hooks are the best place to check several things, like syntax checks,
before the code is stored in the repository.


.. note::

   The code should never been changed before it is commit.


.. note::

   For more information how hooks are invoked from subversion read the `online documentation`_ and
   the chapter to `create hooks`_.

Main Hooks
----------

The hook framework differs between three main hooks start, pre and post. That is also the order in
that the hooks and its sub name are invoked. Also this are the main directories for the
:ref:`listener <svn.listener.introduction>`.


.. _`online documentation`: http://svnbook.red-bean.com/
.. _`create hooks`: http://svnbook.red-bean.com/nightly/de/svn.reposadmin.create.html#svn.reposadmin.create.hooks