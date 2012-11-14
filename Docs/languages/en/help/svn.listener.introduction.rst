.. _svn.listener.introduction:

Introduction
============

A Listener processes the commit information of each commit that is passed to him from the
`hook framework`. The information that are transport to the listener are information about the
user, the commit message and the data that was commited, like directories and files.


While the Info listener is called only once, the Object listener is called for each file in a commit
if there are files.


Therefore listeners are distinguished between a commit :doc:`info listener <svn.listener.info>`
(AbstractInfo) and commit :doc:`object listener <svn.listener.object>` (AbstractObject).
