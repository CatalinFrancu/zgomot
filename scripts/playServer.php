<?php

/**
 * Watches a file and plays sound files based on specifications.
 **/

require __DIR__ . '/../lib/Util.php';

define('DURATION_COMMAND', 'ffprobe -i "%s" -show_entries format=duration -v quiet -of csv="p=0"');
define('PLAY_COMMAND', "mpv '%s' --start=%s --length=%s --really-quiet > /dev/null 2>&1 &");
define('KILL_COMMAND', 'killall mpv');

$file = Config::get('playServer.watchFile');
touch($file);
chmod($file, 0666);

$inot = inotify_init();
$watch = inotify_add_watch($inot, $file, IN_MODIFY);

$events = inotify_read($inot);
while (!($events[0]['mask'] & IN_IGNORED)) { // IN_IGNORED is raised when the file is deleted
  // $events contains some useful info; we only care about the knowledge that $file was modified.

  $now = time();
  $endTimestamp = (int)file_get_contents($file);

  system(KILL_COMMAND);
  if ($now < $endTimestamp) {
    printf("Playing sound until %d, time is now %d\n", $endTimestamp, $now);
    playSound($endTimestamp - $now);
  }
  
  $events = inotify_read($inot);
}

print("Watch file deleted.\n");
fclose($inot);

/*************************************************************************/

// Select a chunk of up $maxDuration seconds and play it. The chunk is selected uniformly at random
// from the available files. Longer files have a higher chance to be selected, as if we were
// concatenating the sound files together.
function playSound($maxDuration) {
  $dir = Config::get('playServer.soundDir');
  $ext = implode(',', Config::get('playServer.soundExt'));

  $files = glob("{$dir}/*.{{$ext}}", GLOB_BRACE);
  $fileData = [];
  $totalDuration = 0;
  foreach ($files as $f) {
    // Grab the duration of the file
    $command = sprintf(DURATION_COMMAND, $f);
    $output = OS::executeAndReturnOutput($command);
    $duration = (int)$output[0];

    $fileData[] = [
      'fileName' => $f,
      'start' => $totalDuration,
      'duration' => $duration,
    ];

    $totalDuration += $duration;
  }

  // sentinel
  $fileData[] = [
    'duration' => $totalDuration,
  ];

  // select an offset at random and see what file it falls into
  $offset = rand() % $totalDuration;
  $i = 0;
  while ($offset >= $fileData[$i + 1]['start']) {
    $i++;
  }

  if ($fileData[$i + 1]['start'] - $offset >= $maxDuration) {
    // We have enough left to play starting from the chosen offset
    $startAt = $offset - $fileData[$i]['start'];
  } else {
    // Go back in the file (but no further than the start of the file
    $startAt = max(0, $fileData[$i]['duration'] - $maxDuration);
  }

  $command = sprintf(PLAY_COMMAND,
                     $fileData[$i]['fileName'],
                     Util::secondsToTime($startAt),
                     Util::secondsToTime($maxDuration));
  print "Running {$command}\n";
  system($command);
}
