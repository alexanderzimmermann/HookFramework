.. _svn.listener.info:

Information listener
====================

A Listener processes the commit information of each commit that is passed to him from the
`hook framework`. The information that are transport to the listener are information about the
user, the commit message and the data that was commited, like directories and files.


(user, message) or the items of a commit.


Therefore listeners are distinguished between a commit info listener (ListenerInfoAbstract) and
commit object listener (ListenerObjectAbstract).