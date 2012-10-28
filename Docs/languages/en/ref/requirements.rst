.. _requirements:

***************************
Hook Framework Requirements
***************************

.. _requirements.introduction:

Introduction
------------

Hook Framework requires a *PHP* 5.3 interpreter with a command line interpreter configured to
handle *PHP* scripts correctly in a command line. Some features require additional extensions.
In most cases the framework can be used without them, although performance may suffer or ancillary
features may not be fully functional.

.. _requirements.version:

PHP Version
^^^^^^^^^^^

The Hook Framework recommends the most current release of *PHP* for critical security and
performance enhancements, and currently supports *PHP* 5.3.6 or later.

Hook Framework has a collection of unit tests, which you can run using PHPUnit 3.3.0 or later.

.. _requirements.extensions:

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

.. include:: requirements.php.extensions.table.rst
