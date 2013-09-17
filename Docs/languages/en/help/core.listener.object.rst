.. _core.listener.object:

Object listener
===============
The `object listener` gets the *Object* object to process the listener. The main intention of this
listener is to handle the files of a commit. Comparing to the :doc:`info listener <core.listener.info>`
that is only called once, this listener is called for each file (object) in the commit.

The files that are passed to the :doc:`process function <core.listener.process>` are already
filtered to the requested files and the filter settings in the
:doc:`register function <core.listener.register>`.


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
and *message* etc. See :ref:`Info Object <core.listener.info>`.


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


getChangedParts()
~~~~~~~~~~~~~~~~~
Returns an Array with all change areas of this file. With this its possible to execute logic that
depends on the data what exactly was changed. This information is divided in some sub objects.

.. code-block:: php

   $aDiff  = $oObject->getChangedParts();

This is an array of Line Objects. This line object provides some more functions.

.. code-block:: php

   getInfo
   getRawLines
   getNewLines
   getOldLines

The `getInfo` method returns an object with the parsed information that is in an unified diff.

.. code-block:: text

   @@ -32,5 +34,19 @@

   getOldStart        => 32
   getOldLength       => 5

   getNewStart        => 34
   getNewLength       => 19


The method `getRawLines` returns the raw lines of an unified diff.

.. code-block:: text

   @@ -35,10 +35,10 @@
    class WhiteFile
    {
    	/**
   -	 * A member var.
   -	 * @var stdClass
   +	 * List with generated random numbers.
   +	 * @var array
    	 */
   -	private $oMember;
   +	private $aNumbers = array();

    	/**
    	 * Init.

The methods `getOldLines` and `getNewLines` will return only these + and - parts as an array.
But this array contains the line number this line has in the new file or had in the old file.
In example above it will looks like this.

.. code-block:: text

   Old lines
   38 => 	 * A member var.
   39 => 	 * @var stdClass
   41 => 	private $oMember;

   New lines
   38 => 	 * List with generated random numbers.
   39 => 	 * @var array
   41 => 	private $aNumbers = array();



.. _`subversion property name`: http://svnbook.red-bean.com/en/1.5/svn.ref.properties.html