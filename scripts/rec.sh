#!/bin/bash

# echo Current date is `date`
# read min sec <<<$(date +'%M %S')
# delay=$(( 3600 - $min*60 - $sec ))
# echo Sleeping $delay seconds till next hour
# sleep $delay
# echo "Recording (almost) an hour"

ROOT=$(dirname $(dirname $0))
YEAR=`date +'%Y'`
MONTH=`date +'%m'`
DAY=`date +'%d'`
HOUR=`date +'%H'`
FILE=$ROOT/mp3/$YEAR/$MONTH/$DAY/$YEAR-$MONTH-$DAY-$HOUR.mp3

mkdir -p $ROOT/mp3/$YEAR/$MONTH/$DAY/

#arecord -d 3590 -f S16_LE -c 1 -r 44100 -t wav | flac - -f --best -o `date +'%Y-%m-%d-%H-%M'`.flac
#arecord -d 3590 -f S16_LE -r 44100 -c 1 -q | ffmpeg -i pipe:0 -qscale:a 2 -loglevel quiet -y $FILE
arecord -d 3590 -f S16_LE -r 16000 -c 1 -D hw:0,0 -q | ffmpeg -i pipe:0 -qscale:a 2 -loglevel quiet -y $FILE
