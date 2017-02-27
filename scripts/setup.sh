#!/bin/bash

# for OS X compatibility, do not use readlink
cd `dirname $0`
CWD=`pwd`
ROOT_DIR=`dirname $CWD`
cd $ROOT_DIR
echo "The root of your client appears to be $ROOT_DIR"

# Create a copy of the config file unless it already exists
if [ ! -e zgomot.conf ]; then
  echo "* copying zgomot.conf.sample to zgomot.conf"
  cp zgomot.conf.sample zgomot.conf
else
  echo "* zgomot.conf already exists, skipping"
fi

mkdir -p templates_c
chmod 777 templates_c

gcc -Wall -O2 -o scripts/clip scripts/clip.c
