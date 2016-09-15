#!/bin/bash

# for OS X compatibility, do not use readlink
cd `dirname $0`
CWD=`pwd`
ROOT_DIR=`dirname $CWD`
cd $ROOT_DIR
echo "The root of your client appears to be $ROOT_DIR"

mkdir -p templates_c
chmod 777 templates_c

gcc -Wall -O2 -o scripts/clip scripts/clip.c
