.. _requirements:

***************************
Hook Framework Requirements
***************************

.. _requirements.overview:

Quick Overview
--------------

The hook framework is a CLI application. So there is no need for a webserver, but the PHP
CLI should be configured (i. e. error_log).

.. include:: requirements.php.extensions.table.txt

.. _requirements.introduction:

Requirements
------------

Depending on what VCS you want to use, the VCS should also be installed.

.. note::

   Actually the only operating system that the hook framework is tested on is Linux.


Different requirements for the different adapter
------------------------------------------------

At the moment the hook framework supports the version control systems git and subversion.

.. _requirements.git:

Git
^^^^


Git 1.7.10 or higher is needed and access to the git commands must be granted.

.. _requirements.subversion:

Subversion
^^^^^^^^^^

First of all you need a subversion server running with already created repositories.
The Hook Framework recommends subversion 1.4 or later.

The hook framework needs to be installed on the same server where subversion is installed.
Direct access to the subversion commands must be granted.


.. _requirements.php:

PHP
-----

PHP Version
^^^^^^^^^^^

Hook Framework requires a *PHP* 5.3 interpreter with a command line interpreter configured to
handle *PHP* scripts correctly in a command line. Some features require additional extensions.
In most cases the framework can be used without them, although performance may suffer or ancillary
features may not be fully functional.

The Hook Framework recommends the most current release of *PHP* for critical security and
performance enhancements, and currently supports *PHP* 5.3.6 or later.

Hook Framework has a collection of unit tests, which you can run using PHPUnit 3.5.0 or later.


Optional
--------

In order to run listener that use other software, you need to install this software they use.
The example repository includes a listener that needs the
`PHP CodeSniffer`_ installed.
Another listener mails the changes to a given address, so you need the mail function for php
configured.

.. _`PHP CodeSniffer`: http://pear.php.net/package/PHP_CodeSniffer
