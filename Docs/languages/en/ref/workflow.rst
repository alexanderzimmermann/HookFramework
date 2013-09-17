.. _workflow:

Workflow
********

This is for internal use. This shows the workflow.

.. code-block:: text

                               User inits "commit", "merge" etc.

                                                 |
                                                 |
                                                \|/

                          +-- Hook -----------------------------------+
                          | - Inits the Hook class                    |
                          | - Inits the Argument class pending on the |
                          |   the Adapter (git, svn)                  |
                          | - Checks                                  |
                          | - Get and parse project dir for listener  |
                          +-------------------------------------------+

                                      /                         \
                                     /                           \
                                   \/_                           _\/

  +-- Adapter SVN ----------------------------+      +-- Adapter Git ----------------------------+
  |- Parse the commited svn files/properties  |      |- Parse the commited git files/properties  |
  |  - the raw diff lines actions A, U, D     |      |  - the raw diff lines actions A, U, D     |
  |  - the diff of each file action           |      |  - the diff of each file action           |
  |  - Creates a info object                  |      |  - Creates a info object                  |
  |  - Creates the commit objects             |      |  - Creates the commit objects             |
  |                                           |      |                                           |
  +-------------------------------------------+      +-------------------------------------------+

                            \                                 /
                             \                               /
                             _\/                           \/_

                          +-- Hook -----------------------------------+
                          | - Process the data to the listener        |
             +---------   |                                           | ---------+
             |            |                                           |          |
             |            +-------------------------------------------+          |
             |                                                                   |
            \|/                                                                 \|/

  +-- Info Listener --------------------------+      +-- Object Listener ------------------------+
  | - Pass the commit data to each info       |      | - Pass each object in commit to           |
  |   info listener                           |      |   each object listener                    |
  | - Execute the process function in each    |      | - Execute the process function in each    |
  |   listener to proceed the listener logic. |      |   listener to proceed the listener logic. |
  +-------------------------------------------+      +-------------------------------------------+

                                                 |
                                                 |
                                                \|/

                          +-------------------------------------------+
                          | - Response the error messages of all      |
                          |   listeners.                              |
                          |                                           |
                          +-------------------------------------------+
