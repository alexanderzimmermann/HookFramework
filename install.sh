#!/bin/bash

#
# Provides a little install script for the hook framework for a fast setup.
#
# Usage: [sudo] ./install.sh /var/svn/REPOSITORY-NAME
#

clear

# Get the install directory.
INSTALL_DIR=`pwd`/

# Get the new repository name.
REPOSITORY=$(basename $1)


## Escape path for sed using bash find and replace
TMP_INSTALL_DIR="${INSTALL_DIR//\//\\/}"


# Check that the repository exists.
if [ -f $1/format ]
then
	# Just copy all templates in the given subversion repository.
	echo "Copy hook files to target $1/hooks"
	echo ""
	cp  $INSTALL_DIR/Docs/svn-templates/* $1/hooks

	# Now replace the "/path/to/hookframework/" path with the actual path of the hook framework.
	echo "Replace default path /path/to/hookframework/ with $INSTALL_DIR"
	echo ""
	find $1/hooks -type f -exec sed -i 's/\/path\/to\/hookframework\//'"$TMP_INSTALL_DIR"'/g' {} \;

	# Copy the example listener.
	echo "Copy example listener $INSTALL_DIR to Repositories/$REPOSITORY"
	echo ""
	mkdir -p $INSTALL_DIR/Repositories/$REPOSITORY
	cp -R $INSTALL_DIR/Repositories/Example/* $INSTALL_DIR/Repositories/$REPOSITORY

	# Done.
	echo "Done."
fi
