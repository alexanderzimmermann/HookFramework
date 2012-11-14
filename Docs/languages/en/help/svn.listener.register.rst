.. _svn.listener.register:

Register the listener
=====================

The register function is called for each listener and tells the hook framework for which sub action
this listener should take care of and or which data objects should be shipped to the process Action.

Depending on the listener type the information is passed to the
:ref:`processAction <svn.listener.process>` method.

In the register method you can also implement some filter rules.
See :ref:`Filter <svn.listener.filter>` (next topic) for detailed information.

Example
-------

.. rubric:: Using the class cache pattern

This example uses Apache with the following .htaccess:

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
   	} // function


Action
~~~~~~
This are the valid values for the action array index.

* commit
* lock
* unlock
* revprop-change

.. code-block:: php

   'commit';


Fileaction
~~~~~~~~~~
In each commit new files are identified with an ``A``, updated files with an ``U``. Deleted files
are identified with a ``D``.

* A
* U
* D

.. code-block:: php

   array('A', 'U');


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

