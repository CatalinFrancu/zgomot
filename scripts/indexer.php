<?php

/**
 * Goes through all the generated clips and prints an index file
 * to be used by the web page.
 * Each line has the format: year month day hour minute second duration.
 **/

$MP3_FILE_PATTERN = '/([0-9]{4})-([0-9]{2})-([0-9]{2})-([0-9]{2})-([0-9]{2})-([0-9]{2})\.mp3/';
       
$ROOT = dirname(__DIR__);
$PROCESSED_DATES_FILE = "{$ROOT}/sound-index/processed-dates.txt";

// remove old index files
// exec("rm -rf {$ROOT}/sound-index/*");

// load the dates we have already processed (map of 'YYYY/MM/DD' => ignored)
$processedDates = @file($PROCESSED_DATES_FILE, FILE_IGNORE_NEW_LINES) ?: [];
$processedDates = array_flip($processedDates);

// get all the daily clip folders containing a .eval file
$evalFiles = [];
exec("find -L {$ROOT}/clip -regextype egrep -regex '.*[0-9]{4}/[0-9]{2}/[0-9]{2}/\.eval' | sort",
     $evalFiles);

foreach ($evalFiles as $evalFile) {
  // check if we have already processed this day
  $matches = [];
  preg_match('~([0-9]{4}/[0-9]{2}/[0-9]{2})/\.eval~', $evalFile, $matches);
  $dateDir = $matches[1];

  if (isset($processedDates[$dateDir])) {
    print "{$dateDir} already processed, skipping\n";
  } else {
    // process all the files in that directory
    $dir = dirname($evalFile);
    $files = scandir($dir);

    foreach ($files as $file) {
      $m = [];
      if (preg_match($MP3_FILE_PATTERN, $file, $m)) {
        $duration = exec("mp3info -p '%S' {$dir}/{$file}");
        $ts = strtotime("{$m[1]}-{$m[2]}-{$m[3]} {$m[4]}:{$m[5]}:{$m[6]}");
        $dayOfWeek = date('w', $ts);
        $dayPrefix = ($dayOfWeek == 0 || $dayOfWeek == 6) ? 'we' : 'wd';

        $line = "{$ts} {$duration}\n";
        $fileName = "{$ROOT}/sound-index/{$dayPrefix}{$m[4]}.txt";
        file_put_contents($fileName, $line, FILE_APPEND);
        print "$file => $line";
      }
    }

    // add the directory to the process dates file
    file_put_contents($PROCESSED_DATES_FILE, "{$dateDir}\n", FILE_APPEND);
  }
}
