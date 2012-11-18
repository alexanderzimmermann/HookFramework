.. _svn.repositories.structure:

Structure in the Repositories
=============================

It is often desirable to use a purely *PHP*-based configuration file. The following code illustrates
how easily this can be accomplished:

.. code-block:: text

    Repositories/
        /Example
            /logs
                common.log
            /Post
                Mailing.php
                Diff.php
            /Pre
                Id.php
                Style.php
                Syntax.php
            /Start
                User.php
            config.ini
        /Example2
            /Pre
                Style.php
                Syntax.php
            config.ini

