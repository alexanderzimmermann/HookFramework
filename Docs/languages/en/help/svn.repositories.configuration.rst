.. _svn.repositories.configuration:

Configuration
=============

In every repository path, you can put a ``config.ini`` to store configuration settings for the
listener. So it is easier to adjust variable data and keep the listener code as is when copy
listener to other repositories.

This configuration is parsed and pass to the listener.

.. code-block:: text

    Repositories/
        /Example
            /Post
                Mailing.php
            /Pre
                Style.php
            config.ini
        /Example2
            /logs
            /Pre
                Style.php
            config.ini

settings in config.ini file
---------------------------
The hierarchy in the configuration is similar to the directories of the listener.

Every section header tells the main hook and the sub-hook: ``[Main:ListenerClassName]``.
So for a listener named *Style* in directory *Pre* you set it to ``[Pre:Style]``.

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


Example for several listener.

.. code-block:: text

   ; Configuration file for the repository
   ; Available since 2.0

   ; [MainHook : Listener Class / File Name]
   ; Values that the Listener can use.

   [Start:Start]
   Users[]                       = Duchess
   Users[]                       = Enya
   Users[]                       = Zora
   DenyMsg                       = "Access not allowed"


   [Pre:Style]
   Filter.Directory[]            = Filter/Filtered
   Filter.Files[]                = Filter/Test.php
   Filter.WhitelistFiles[]       = Filter/Filtered/Whitelist.php
   Filter.WhitelistDirectories[] = Filter/Filtered/Whitelist
   Standard                      = PEAR
   Style.LineLength              = 4

   [Pre:MessageStrict]
   ErrorMessage                  = "Please use complete sentences."


   [Post:Mailing]
   Mail[]                        = alex@azimmermann.com
   Mail[]                        = mail@example.com