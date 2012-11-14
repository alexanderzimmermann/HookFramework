.. _svn.listener.object:

Object listener
===============
The `object listener` gets the *Object* object to process the listener. The main intention of this
listener is to handle the files of a commit. Comparing to the :doc:`info listener <svn.listener.info>`
that is only called once, this listener is called for each file (object) in the commit.

The files that are passed to the :doc:`process function <svn.listener.process>` are already
filtered to the requested files and the filter settings in the
:doc:`register function <svn.listener.register>`.


Available Functions for *Object* object
---------------------------------------
Here is a list of the available function of the *Object* object that is passed to the listener in
the process function.


getAction()
~~~~~~~~~~~~
Returns the action **A**, **U** and **D** in that upper case format.


getIsDir()
~~~~~~~~~~~~
Is **true** whether the file / path is a directory or **false** if not.


getObjectPath()
~~~~~~~~~~~~~~~
Returns the full path to the object.

- ``reposname/trunk/path/to/file/script.php``
- ``reposname/branches/2.1/path/to/file/script.php``
- ``reposname/tags/2.1.0/path/to/file/script.php``


getRealPath()
~~~~~~~~~~~~~
Comparing to the example paths in ``getObjectPath``, this paths are truncated by repositoryname, the
 common directories used to separate development branches like *trunk* and *branches*, *tags* and
 its sub directories. In this example *2.1* and *2.1.0*.

- ``reposname/trunk/path/to/file/script.php``
- ``reposname/branches/2.1/path/to/file/script.php``
- ``reposname/tags/2.1.0/path/to/file/script.php``


getInfo()
~~~~~~~~~
Here it is possible to get access on the information that was shiped with the commit like *user*
and *message* etc. See :ref:`Info Object <svn.listener.info>`.


getTmpObjectPath()
~~~~~~~~~~~~~~~~~~
Returns the path and file to the temporary created file that is shipped with the commit.

.. caution::

   Be careful what files you handle here. This is a possible security break.

Example use in a object listener.

.. code-block:: php

   $aLines    = array();
   $sCommand  = 'phpcs --standard=PEAR --tab-width=4 ';
   $sCommand .= $oObject->getTmpObjectPath();

   exec($sCommand, $aLines);


getActualProperties()
~~~~~~~~~~~~~~~~~~~~~
Returns an Array with the actual subversion properties that are set on this object. The `subversion
property name`_ is the index and the value is the property object.

.. code-block:: php

   array(
        'svn:keywords'   => *Property object*
        'svn:ignore'     => *Property object*
        'svn:executable' => *Property object*
   );


The *Property object* provides the function ``getNewValue``.

getChangedProperties()
~~~~~~~~~~~~~~~~~~~~~~
Like in ``getActualProperties`` it also returns an Array with the actual subversion properties that
are set on this object, but only the changed once.

.. code-block:: php

   array(
        'svn:keywords'   => *Property object*
        'svn:ignore'     => *Property object*
        'svn:executable' => *Property object*
   );

The *Property object* provides therefore also the function ``getOldValue``.

.. code-block:: php

   $sValue = $oProperty->getNewValue();
   if ('Id' !== $sValue)
   {
    	$oObject->addError('Please add the "Id" value to the svn:keywords tag.');
   } // if


getChangedLines()
~~~~~~~~~~~~~~~~~
Returns the raw difference lines of the commit in an Array.

Example of changed lines, already convert to a whole text.

.. code-block:: text

   Modified: hookframework/trunk/tmp/newfolder1/newfolder1_1/correct_file1.php
   ===================================================================
   --- hookframework/trunk/tmp/newfolder1/newfolder1_1/correct_file1.php	2008-12-20 19:20:59 UTC (rev 140)
   +++ hookframework/trunk/tmp/newfolder1/newfolder1_1/correct_file1.php	2009-01-03 12:11:06 UTC (txn 140-1)
   @@ -5,7 +5,7 @@
     * @package    Test
     * @subpackage Newfolder
     * @author     Alexander Zimmermann <alex@azimmermann.com>
   - * @version    SVN: $Id: $
   + * @version    SVN: $Id$
     */

    /**
   @@ -34,4 +34,13 @@
    	public function init()
    	{
    	} // function
   +
   +	/**
   +	 * Hinzufuegen.
   +	 * @return void
   +	 * @author Alexander Zimmermann <alex@azimmermann.com>
   +	 */
   +	public function addItem()
   +	{
   +	} // function
    } // class



.. _`subversion property name`: http://svnbook.red-bean.com/en/1.5/svn.ref.properties.html