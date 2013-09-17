.. _core.listener.directory:

Directory
=========

The sub-directories usually contain the listener object files depending on the VCS. The listener *scanner*
does only scan the *Main*-*Sub* directory format. It does not scan recursively.

It is also possible to store some files that are helper objects to a listener object. This files
will be ignored when the hook framework looks into the listener directories and it doesn't implement
the abstract class and interface.

.. note::

   See also :ref:`git <core.repositories.git.structure>` or
   :ref:`subversion <core.repositories.subversion.structure>` for more information.

Valid Listener Objects
----------------------

A valid listener should implement the correct interface and extends the correct abstract class.

For info listener its `Info` and `InfoAbstract`.

For object listener its `Object` and `ObjectAbstract`.

Namespaces
----------

It is important that the namespaces in the listener files look like the structure of the directory.

First the name of the VCS repository in upper case and then the sub-directory.

.. note::

   If you used the :ref:`installation script <introduction.installation>` this is already done.

Example:

.. code-block:: php

   namespace ExampleSvn\Pre;

   namespace ExampleGit\Client;

