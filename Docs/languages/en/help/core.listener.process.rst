.. _core.listener.process:

Processing the listener
=======================
The ``processAction`` is executed once for a :ref:`Info listener <core.listener.info>` and for each
object in the :ref:`Object listener <core.listener.object>`.

Within the processAction method feel free to implement whatever you need to analyze, to check on
the commited file.

.. attention::

   It is not a very good idea to change content of files that being commited.


Here is an example of a ``processAction`` to check the syntax of a php file that's being added or
updated.

.. code-block:: php
   :linenos:

   /**
    * Execute the action.
    * @param Object $oObject Directory / File-Object.
    * @return void
    */
   public function processAction(Object $oObject)
   {
       $aLines = array();
       $sCmd   = 'php -l ' . $oObject->getTmpObjectPath() . ' 2>&1';
       exec($sCmd, $aLines);

       if (true === empty($aLines)) {
           return;
       }

       $sMessage  = 'No syntax errors detected in ';
       $sMessage .= $oObject->getTmpObjectPath();

       if (count($aLines) === 1) {
           if ($aLines[0] === $sMessage) {
               return;
           }
       }

       $oObject->addErrorLines($aLines);
   }
