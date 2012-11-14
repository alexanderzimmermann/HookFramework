.. _svn.listener.directory:

Directory
=========

The directore Pre / Post / Start usually contain the listener object files.

It is also possible to store some files that are helper objects to a listener object. This files
will be ignored when the hook framework looks into the listener directories.


Namespaces
----------

It is important that the namespace is simply like the structure of the directory.

First the name of the subversion repository in upper case.
Then the `main hook name`_.

:ref:`listener <svn.listener.introduction>`.

Example:

.. code-block:: php

   namespace Example\Pre;


Valid Listener Objects
----------------------

A valid listener should implement the correct interface and extends the correct abstract class.

For info listener its `Info` and `InfoAbstract`.

For object listener its `Object` and `ObjectAbstract`.

.. _`main hook name`: http://pear.php.net/manual/en/pyrus.php