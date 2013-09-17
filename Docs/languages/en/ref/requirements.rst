.. _requirements:

***************************
Hook Framework Requirements
***************************

.. _requirements.overview:

Quick Overview
^^^^^^^^^^^^^^

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

PHP Version
^^^^^^^^^^^

Hook Framework requires a *PHP* 5.3 interpreter with a command line interpreter configured to
handle *PHP* scripts correctly in a command line. Some features require additional extensions.
In most cases the framework can be used without them, although performance may suffer or ancillary
features may not be fully functional.

The Hook Framework recommends the most current release of *PHP* for critical security and
performance enhancements, and currently supports *PHP* 5.3.6 or later.

Hook Framework has a collection of unit tests, which you can run using PHPUnit 3.3.0 or later.

.. _requirements.php_extensions:

PHP Extensions
^^^^^^^^^^^^^^

You will find a table listing all extensions typically found in *PHP* and how they are used in Zend Framework
below. You should verify that the extensions on which Zend Framework components you'll be using in your application
are available in your *PHP* environments. Many applications will not require every extension listed below.

A dependency of type "hard" indicates that the components or classes cannot function properly if the respective
extension is not available, while a dependency of type "soft" indicates that the component may use the extension if
it is available but will function properly if it is not. Many components will automatically use certain extensions
if they are available to optimize performance but will execute code with similar functionality in the component
itself if the extensions are unavailable.

