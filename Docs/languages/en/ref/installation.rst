.. _introduction.installation:

************
Installation
************

First, read the :ref:`requirements <requirements>`.

There are two ways to install or to activate the hookframework for the version control system.
The easiest way is to use the installation script. Or the manually installation.

Download the Hookframework
==========================

Clone the repository from `GitHub`_ or download the `latest version`_.

.. code-block:: console

   $: git clone https://github.com/alexanderzimmermann/HookFramework.git

This will create a directory **HookFramework** in the actual directory.


Install the hooks via install script
====================================

The script just needs 2 parameters, which version control system you use and the path to the
repository you want to activate the hookframework to.

Assuming that you are in the directory of the hook framework, just type the following command:

.. code-block:: console

    $: [sudo] ./install.sh git /path/to/git/directory
    $: [sudo] ./install.sh svn /var/svn/REPOSITORY-NAME

That's it, now adjust the :ref:`listener <core.listener.introduction>` to your needs.


Install the hooks manually
==========================

Configuration
-------------

* Copy or rename the ``config-dist.ini`` file to ``config.ini`` in the main folder of the Hookframework.
* Adjust the path for the subversion executable (mostly ``/usr/bin/`` will fit) to get access to the version control commands (git, svn, svnlook).
* Set the ``logfile`` path where you want to have the logging. Be sure the file is writable to PHP.

Activate the hooks
------------------

This depends on the version control system you use. Choose from the list below how to activate the hooks.

* :ref:`Git <git.git.installation>`
* :ref:`Subversion <svn.subversion.installation>`

.. _`GitHub`: http://github.com/
.. _`latest version`: https://github.com/alexanderzimmermann/HookFramework/archive/master.zip
