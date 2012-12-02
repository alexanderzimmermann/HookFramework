.. _svn.listener.introduction:

Introduction
============

A Listener processes the commit information of each commit that is passed to it from the
`hook framework`. The information that are transport to the listener are information about the
user, the commit message and the data that was commited, like directories and files.


While the Info listener is called only once, the Object listener is called for each file or
directory in a commit.

Therefore listeners are distinguished between a commit :doc:`info listener <svn.listener.info>`
(AbstractInfo) and commit :doc:`object listener <svn.listener.object>` (AbstractObject).

In the listener you can implement every logic you want, with the commit information.

* Check if the user is allowed to commit (useful when not use webdav or subversion acl).
* Check if there are syntax errors in the commited files.
* Check the code against a style guide.
* Check that a commit message is given.

If now some of this checks is not against your `rules` you can avoid the check in within the Start
and Pre type hooks by using the `addError` or `addErrorLines`.

For every listener that is processed those lines will be return to the IDE or console you are
commit from.

The Post hooks only have some logging or protocol functionality.
