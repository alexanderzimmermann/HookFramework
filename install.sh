#!/bin/bash

#
# Provides a little install script for the hook framework for a fast setup.
#
# Usage SVN: [sudo] ./install.sh svn /var/svn/REPOSITORY-NAME
# Usage GIT: [sudo] ./install.sh git /path/to/git/directory
#


# ###########################################
#
# Install the files.
# param $1 string $INSTALL_DIR
# param $2 string $HOOK_DIR
# param $3 string $REPOSITORY
# param $4 string $REPOSITORY_DIR
# paara $5 string $VCS
# param $6 string $EXAMPLE
#

InstallMain() {

    clear

    INSTALL_DIR=$1
    HOOK_DIR=$2
    REPOSITORY=$3
    REPOSITORY_DIR=$4
    VCS=$5
    EXAMPLE=$6

    # Escape path for sed using bash find and replace.
    TMP_INSTALL_DIR="${INSTALL_DIR//\//\\/}"

    # Just copy all templates in the given subversion repository.
    echo "* Copy hook files to target $HOOK_DIR"
    echo ""
    cp -v $INSTALL_DIR/Docs/templates/$VCS/* $HOOK_DIR

    # Now replace the "/path/to/hookframework/" path with the actual path of the hook framework.
    echo "* Replace default path /path/to/hookframework/ with $INSTALL_DIR in $HOOK_DIR"
    echo ""
    find $HOOK_DIR -type f -exec sed -i 's/\/path\/to\/hookframework\//'"$TMP_INSTALL_DIR"'/g' {} \;

    # Copy the example listener.
    echo "* Copy example listener $INSTALL_DIR"Repositories/"$EXAMPLE/* to Repositories/$REPOSITORY"
    echo ""
    mkdir -vp $REPOSITORY_DIR
    cp -vR "$INSTALL_DIR"Repositories/"$EXAMPLE"/* $REPOSITORY_DIR
    echo ""

    # Parse the listener php files and adjust the namespace.
    echo "* Change namespace in $REPOSITORY_DIR/ from namespace $EXAMPLE to namespace $REPOSITORY"
    find $REPOSITORY_DIR -type f -exec sed -i 's/'"namespace $EXAMPLE"'/'"namespace $REPOSITORY"'/g' {} \;

    return 0;
}


# ###########################################
#
# Install the subversion stuff.
# param  string  Target dir.

InstallSvn() {

    # Get the install directory.
    INSTALL_DIR=`pwd`/

    # Define the hook dir.
    HOOK_DIR=$1/hooks/

    # Get the new repository name.
    REPOSITORY=$(basename $1)

    # Target repository directory
    REPOSITORY_DIR="$INSTALL_DIR"Repositories/$REPOSITORY


    # Check that the repository exists.
    if [ -f $1/format ]
    then
        # Copy and setup all stuff.
        InstallMain $INSTALL_DIR $HOOK_DIR $REPOSITORY $REPOSITORY_DIR svn ExampleSvn
    else
        return 1;

    fi

    return 0;
}


# ###########################################
#
# Install the git stuff.
# param  string  Target dir.

InstallGit() {

    # Get the install directory.
    INSTALL_DIR=`pwd`/

    # Define the hook dir.
    HOOK_DIR=$1/.git/hooks/

    # Get the new repository name.
    REPOSITORY=$(basename $1)

    # Target repository directory
    REPOSITORY_DIR="$INSTALL_DIR"Repositories/$REPOSITORY


    # Check that the repository exists.
    if [ -d $HOOK_DIR ]
    then
        # Copy and setup all stuff.
        InstallMain $INSTALL_DIR $HOOK_DIR $REPOSITORY $REPOSITORY_DIR git ExampleGit
    else
        return 1;
    fi

    return 0;
}



# ###########################################
#
# In case of errors, show the usage help
#

function ShowUsage() {

    vcs=`echo $1 | tr '[:upper:]' '[:lower:]'`

    if [ "$1" = "svn" ]
    then
        echo "Usage SVN: [sudo] ./install.sh svn /var/svn/REPOSITORY-NAME"
    elif [ "$1" = "git" ]
    then
        echo "Usage GIT: [sudo] ./install.sh git /path/to/git/directory"
    else
        echo "Usage SVN: [sudo] ./install.sh svn /var/svn/REPOSITORY-NAME"
        echo "Usage GIT: [sudo] ./install.sh git /path/to/git/directory"
    fi
}


# #####################################
#
# *** MAIN ***
#

case $1 in
  svn|SVN|Svn)
    InstallSvn $2
    return_val=$?
    ;;
  git|GIT|Git)
    InstallGit $2
    return_val=$?
    ;;
  *)
    return_val=1
    ;;
esac

# If an error occurs, show the usage hints.
if [ "$return_val" -eq 1 ]
then
    ShowUsage $1
fi

echo ""
echo "Install completed"
echo ""
echo ""