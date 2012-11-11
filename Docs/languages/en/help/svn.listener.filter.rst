.. _svn.listener.filter:

Filter
======

Since 2.1.0 the filtering of files and or directories is much more easier.

Filtering is now possible with the `real path` of the file in the subversion directories (Before
that you had to filter with all branches, tags and so on).

Directories trunk, branches and tags and the first directory in branches and tags will be deleted
in the path, so that filtering with the `real path` is very easy.

Examples:
hookframework/trunk/library/Hook/Core/Hook.php will be library/Hook/Core/Hook.php or
hookframework/branches/2.1/library/Hook/Core/Hook.php will also be library/Hook/Core/Hook.php

.. code-block:: text

   hookframework/trunk/library/Hook/Core/Hook.php          =>   library/Hook/Core/Hook.php
   hookframework/branches/2.1/library/Hook/Core/Hook.php   =>   library/Hook/Core/Hook.php
   hookframework/tags/2.1.0/library/Hook/Core/Hook.php     =>   library/Hook/Core/Hook.php


Filter Types
~~~~~~~~~~~~

With this 4 functions, files can be

.. code-block:: text

   addDirectoryToFilter
   addDirectoryToWhitelist
   addFileToFilter
   addFileToWhitelist


addDirectoryToFilter
--------------------

This simple ignores all files and directories in `/path/to/directory/filterdir/` within the listeners.

.. code-block:: php

   $this->oObjectFilter->addDirectoryToFilter('/path/to/directory/filterdir/');


addDirectoryToWhitelist
-----------------------

This simple removes all files and directories in `'/path/to/directory/whitedir/'` that they
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


Deny folder, but allow a subfolder

