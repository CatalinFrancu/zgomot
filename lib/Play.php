<?php

class Play {
  const DURATION_COMMAND = 'ffprobe -i "%s" -show_entries format=duration -v quiet -of csv="p=0"';
  const PLAY_COMMAND = "mpv '%s' --start=%s --length=%s --really-quiet --log-file=/tmp/mpv.log > /dev/null 2>&1 &";
  const KILL_COMMAND = 'killall mpv';

  static function killAllSounds() {
    system(self::KILL_COMMAND);
  }

  // Select a chunk of up $maxDuration seconds and play it. The chunk is selected uniformly at random
  // from the available files. Longer files have a higher chance to be selected, as if we were
  // concatenating the sound files together.
  static function playSound($maxDuration) {
    $dir = Config::get('play.soundDir');
    $ext = implode(',', Config::get('play.soundExt'));

    $files = glob("{$dir}/*.{{$ext}}", GLOB_BRACE);
    $fileData = [];
    $totalDuration = 0;
    foreach ($files as $f) {
      // Grab the duration of the file
      $command = sprintf(self::DURATION_COMMAND, $f);
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
      'start' => $totalDuration,
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

    $command = sprintf(self::PLAY_COMMAND,
                       $fileData[$i]['fileName'],
                       Util::secondsToTime($startAt),
                       Util::secondsToTime($maxDuration));
    error_log($command);
    system($command);
  }
}
