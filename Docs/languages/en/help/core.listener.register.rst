.. _core.listener.register:

Register the listener
=====================

The register function is called for each listener and tells the hook framework for which sub action
this listener should take care of and or which data objects should be transport to the process Action.

Depending on the listener type the information is passed to the
:ref:`processAction <core.listener.process>` method.

In the register method you can also implement some filter rules.
See :ref:`Filter <core.listener.filter>` for detailed information.

Example info listener
---------------------

Example to register a info listener for the specified sub-action.

.. code-block:: php
   :linenos:

   /**
    * Register the action.
    * @return string
    */
   public function register()
   {
       return 'commit';
   }


Example object listener
-----------------------

Example to register a object listener for the specified sub-action and tell which files this listener
requests.

.. code-block:: php
   :linenos:

   /**
    * Register the action.
    * @return array
    */
   public function register()
   {
       return array(
               'action'     => 'commit',
               'fileaction' => array(
                                'A', 'U'
                               ),
               'extensions' => array('PHP'),
               'withdirs'   => false
              );
   }

Possible elements for the register array
----------------------------------------

Action
~~~~~~
The valid ``action`` values differ depending on the VCS you use in repository.

**Subversion**

This are the valid values for the action array index for subversion in Start, Pre and Post.

* commit
* lock
* unlock
* revprop-change

.. code-block:: php

   'commit';

**Git**

This are the valid values for the action array index for **git**.

* client

  * pre-commit
  * prepare-commit-msg
  * commit-msg
  * post-commit
  * applypatch-msg
  * pre-applypath
  * post-applypatch
  * pre-rebase
  * post-checkout
  * post-merge

* server

  * pre-receive
  * update
  * post-receive


Fileaction
~~~~~~~~~~

**Subversion**

In each commit new files are identified with an ``A``, updated files with an ``U``. Deleted files
are identified with a ``D``.

* A addition of a file
* U updated file.
* D deleted file.

.. code-block:: php

   array('A', 'U');

**Git**

* A addition of a file
* C copy of a file into a new one
* D deletion of a file
* M modification of the contents or mode of a file
* R renaming of a file
* T change in the type of the file
* U file is unmerged (you must complete the merge before it can be committed)


extensions
~~~~~~~~~~
Tell the hook framework what type of file the listener should take care of. Use upper case to
identify the file type.

.. code-block:: php

   array('PHP', 'PHTML');


withdirs
~~~~~~~~
Set this switch whether to *true* if you want to use the listener also for directories. Set this
to *false* if you don't need the directory names.

.. note::

   In **git** this parameter has no function, cause git doesn't keep empty directories.
