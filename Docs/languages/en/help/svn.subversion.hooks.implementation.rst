.. _svn.subversion.hooks.implementation:

Hook implementation
===================

This example shows how the hook framework is called.

.. code-block:: text
   :linenos:

   REPOS="$1"
   TXN="$2"

   /path/to/hookframework/Hook "$REPOS" "$TXN" pre-commit >&2 || exit 1

   exit 0
