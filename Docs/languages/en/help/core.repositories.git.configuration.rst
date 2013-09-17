.. _core.repositories.git.configuration:

Git
====



Example for several listener in a git configuration file.

.. code-block:: text

   ; Configuration file for the repository
   ; Available since 2.0

   ; [MainHook : Listener Class / File Name]
   ; Values that the Listener can use.

   [Client:PreCommit]
   Users[]                       = Duchess
   Users[]                       = Enya
   Users[]                       = Zora
   DenyMsg                       = "Access not allowed"


   [Client:Style]
   Filter.Directory[]            = Filter/Filtered
   Filter.Files[]                = Filter/Test.php
   Filter.WhitelistFiles[]       = Filter/Filtered/Whitelist.php
   Filter.WhitelistDirectories[] = Filter/Filtered/Whitelist
   Standard                      = PSR2
   Style.LineLength              = 4

   [Client:MessageStrict]
   ErrorMessage                  = "Please use complete sentences."


   [Client:Mailing]
   Mail[]                        = alex@azimmermann.com
   Mail[]                        = mail@example.com