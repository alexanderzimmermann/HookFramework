.. _core.repositories.subversion.configuration:

Subversion
==========

Example for several listener for subversion.

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