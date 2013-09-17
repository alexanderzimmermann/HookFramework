.. _git.repositories.configuration:

Configuration
=============

In every repository path, you can put a ``config.ini`` file, to store configuration settings for the
listener. So it is easier to adjust variable data and keep the listener code as is when copy
listener to other repositories.

This configuration is parsed and passed to the listener.

settings in config.ini file
---------------------------
The hierarchy in the configuration is similar to the directories of the listener.

Every section header tells the main hook and the sub-hook: ``[Main:ListenerClassName]``.
So for a listener named *Style* in directory *Pre* you set it to ``[Pre:Style]``.
For a listener named *Syntax* in directory *Client* you set it to ``[Client:Syntax]``.

In the section you can put every property setting you need in your listener. For multiple values
you can use the ``[]``.

Normally you only can set on property. But if you need multiple arrays to divide section values it
is possible to do this with a *point* separator.

.. code-block:: text

   Filter.Directory[] = Filter/Filtered
   Filter.Directory[] = Filter/Filtered2

This will produce an array like.

.. code-block:: php

   array(
    'Filter' => array(
                 'Directory' => array(
                                 'Filter/Filtered',
                                 'Filter/Filtered2'
                                )
                )
   );

See the example configuration for :ref:`git <core.repositories.git.configuration>` and
:ref:`subversion <core.repositories.subversion.configuration>`.