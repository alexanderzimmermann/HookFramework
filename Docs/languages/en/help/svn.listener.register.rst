.. _svn.listener.register:

Register Processing the listener
================================

The register function is called for each listener and tells the hook framework which data objects
 should be shipped to the process Action.

Example
^^^^^^^

.. rubric:: Using the class cache pattern

This example uses Apache with the following .htaccess:

.. code-block:: php
   :linenos:

   	/**
   	 * Register the action.
   	 * @return array
   	 * @author Alexander Zimmermann <alex@azimmermann.com>
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
------

* commit
* lock
* unlock
* revprop-change

Fileaction
----------

In each commit new files are identified with an ``A``, updated files with an ``U``. Deleted files
are identified with a ``D``.

* A
* U
* D

extensions
----------

Tell the hook framework what type of file the listener should take care of. Use upper case to
 identify the file type.

.. code-block:: php

   array('PHP', 'PHTML');


withdirs
--------

Set this switch wether to *true* if you want to use the listener also for directories. Set this
 to *false* if you don't need the directory names.

