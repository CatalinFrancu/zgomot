<?php

/**
 * Watches a file and plays sound files based on specifications.
 **/

require __DIR__ . '/../lib/Util.php';

$file = Config::get('playServer.watchFile');

touch($file);
chmod($file, 0666);

$inot = inotify_init();
$watch = inotify_add_watch($inot, $file, IN_MODIFY);

$events = inotify_read($inot);
while (!($events[0]['mask'] & IN_IGNORED)) { // IN_IGNORED is raised when the file is deleted

  // $events contains some useful info; we only care about the knowledge that $file was modified.
  $endTimestamp = (int)file_get_contents($file);
  while (time() < $endTimestamp) {
    printf("Doing something until %d, time is now %d\n", $endTimestamp, time());
    playSound();
  }

  $events = inotify_read($inot);
}

print("Watch file deleted.\n");
fclose($inot);

/*************************************************************************/

function playSound() {
  $len = rand(1, 5);
  print "Playing sound of length $len\n";
  sleep($len);
}
