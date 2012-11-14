.. _project-structure:

****************************************************
Recommended Project Structure for the Hook Framework
****************************************************

.. _project-structure.overview:

Overview
--------

The following directory structure is simple and divided in 4 main areas. Documentation, library, Repositories and tests.

.. _project-structure.project:

Recommended Project Directory Structure
---------------------------------------

.. code-block:: text
   :linenos:

   <project name>/
       Docs/
       library/
       Repositories/
           Example/
               logs/
               Post/
               Pre/
               Start/
       tests/

The following describes the use cases for each directory as listed.

- **Docs/**: This directory contains documentation, either generated or directly authored.

- **library/**: This directory is for the common libraries on which the application depends, and should be on the *PHP*
  ``include_path``. Developers should place their application's library code under this directory in a unique
  namespace, following the guidelines established in the *PHP* manual's `Userland Naming Guide`_, as well as those
  established by Zend itself. This directory may also include the Hook Framework itself; if so, you would house it in
  ``library/Hook/``.

- **Respositories/**: This directory contains your application. It will house the *MVC* system, as well as
  configurations, services used, and your bootstrap file.

  - **Example/**: This is the main directory corresponding to your subversion repository name.
                  If you need you can also put some directories or code files here.

    - **Post/**, **Pre/**, and **Start/**: These directories serve as the listener for all post, pre and start actions.
      The subactions are -commit -rev-prop-change are not separated but this can be done within in the `register` function of the `listener object`.

    - **logs/**: These directories will contain the logs for the repository stuff of the hookframework. You can also put logs that you need in this directory.

- **tests/**: This directory contains the tests for the Hook library tests. These are written in PHPUnit.


.. _`Userland Naming Guide`: http://www.php.net/manual/en/userlandnaming.php
