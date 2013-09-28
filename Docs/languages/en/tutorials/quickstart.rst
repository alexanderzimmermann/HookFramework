.. _tutorials.quickstart.intro:

Quick start
===========

Overview
--------

* `Clone`_ or `download`_ the Hookframework.
* Install the hooks.
* Configure

Clone or Download
-----------------

.. code-block:: console

   $: git clone https://github.com/alexanderzimmermann/HookFramework.git

.. code-block:: console

   $: wget https://github.com/alexanderzimmermann/HookFramework/archive/master.zip
   $: unzip master.zip


Installation
------------

.. code-block:: console

   $: cd HookFramework
   $: [sudo] ./install.sh git /path/to/git/existing/directory/PROJECT
   $: vim Repositories/PROJECT/config.ini


Configure
---------

Adjust, remove or delete configurations to your needs and save the file.

.. code-block:: text

   [Pre:StyleIncrement]
   Standard                      = PSR2
   Style.TabWidth                = 4

That's it. Now make commits in your *PROJECT*. If the comment is empty or the source code in your
file doesn't fit the *PSR2* standards, an error will occur in the IDE or console.


.. _`Clone`: https://github.com/alexanderzimmermann/HookFramework.git
.. _`download`: https://github.com/alexanderzimmermann/HookFramework/archive/master.zip