.. _core.listener.introduction:

Introduction
============

A Listener processes the commit information of each commit that is passed to it from the
`hook framework`. The information that are transport to the listener are information about the
user, the commit message and the data that was commited, like directories and files.


While the Info listener is called only once, the Object listener is called for each file or
directory in a commit.

Therefore listeners are distinguished between a commit :doc:`info listener <core.listener.info>`
(AbstractInfo) and commit :doc:`object listener <core.listener.object>` (AbstractObject).

In the listener you can implement every logic you want, with the commit information.

For instance

* Check if the user is allowed to commit (useful when not using webdav or subversion acl).
* Check if there are syntax errors in the commited files.
* Check the code against a style guide.
* Check that a commit message is meaningful.

If now this checks doesnt match your guidelines and you want to prevent the
check in, add some helpfully messages by by using the functions `addError` for one message
or `addErrorLines` for multiple message lines.

When error messages are given, the hook framework will exit with non zero code, that prevents the commit.

.. note:: text

   Post hooks only have logging or protocol character. They can exit with errors but that has no
   effect on the commit anymore.

.. code-block:: php

   $oObject->addErrorLines($aLines);

   $oInfo->addError($sErrorMessage);

For every listener that is processed those lines will be return to the IDE or console you are
commit from.
