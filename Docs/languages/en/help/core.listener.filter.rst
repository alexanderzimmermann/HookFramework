.. _core.listener.filter:

Filter
======

Sometimes it is necessary, to filter files or directories even that they match the
:ref:`register settings<core.listener.register>`.


.. note::

  In subversion filtering is possible with the `real path` of the file in the subversion directories.
  Directories trunk, branches and tags and the first directory in branches and tags will be removed
  in the path, so that filtering with the `real path` is very easy.

  hookframework/trunk/library/Hook/Core/Hook.php will be library/Hook/Core/Hook.php or
  hookframework/branches/2.1/library/Hook/Core/Hook.php will also be library/Hook/Core/Hook.php

.. code-block:: text

   hookframework/trunk/library/Hook/Core/Hook.php          =>   library/Hook/Core/Hook.php
   hookframework/branches/2.1/library/Hook/Core/Hook.php   =>   library/Hook/Core/Hook.php
   hookframework/tags/2.1.0/library/Hook/Core/Hook.php     =>   library/Hook/Core/Hook.php
   hookframework/library/Hook/Core/Hook.php                =>   library/Hook/Core/Hook.php


Filter Rules
~~~~~~~~~~~~

With this 4 functions, files and directories can be filtered in the listener, so that the elements will not passed to the listener.

.. code-block:: php

   $this->addDirectoryToFilter('dir-1');
   $this->addDirectoryToWhitelist('dir-2');
   $this->addFileToFilter('file-1');
   $this->addFileToWhitelist('file-2');


addDirectoryToFilter
--------------------

This ignores all files and directories and subdirectories in `/path/to/directory/filterdir/` within the listeners.

.. code-block:: php

   $this->oObjectFilter->addDirectoryToFilter('/path/to/directory/filterdir/');


addDirectoryToWhitelist
-----------------------

This removes all files and directories and subdirectories in `'/path/to/directory/whitedir/'`, so that they
will be recognized in the listener.

.. code-block:: php

   $this->oObjectFilter->addDirectoryToWhitelist('/path/to/directory/whitedir/');

addFileToFilter
---------------

This filters a file for the listener.

.. code-block:: php

   $this->oObjectFilter->addFileToFilter('/path/to/file/filter.file');


addFileToWhitelist
------------------

This removes a specified file from the ignore list, so that it will be recognized in the filter.

.. code-block:: php

   $this->oObjectFilter->addFileToWhitelist('/path/to/file/white.file');

.. note::

   With the white list functions it is possible to allow elements that where filtered with the directory filter.