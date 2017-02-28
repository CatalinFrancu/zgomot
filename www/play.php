<?php

require '../lib/Util.php';

$durationIndex = Request::get('durationIndex');

if ($durationIndex != null) {
  $durations = Config::get('playServer.duration');
  $duration = $durations[$durationIndex];

  $endTimestamp = time() + $duration;
  $fileName = Config::get('playServer.watchFile');
  file_put_contents($fileName, $endTimestamp);
  chmod($fileName, 0666);

  FlashMessage::add(sprintf('Am pornit sunetul până la %s.',
                            date('H:i:s', $endTimestamp)),
                    'success');
  Util::redirect('play.php');
}

Smart::assign('local', false);
Smart::assign('durations', Config::get('playServer.duration'));
Smart::display('play.tpl');
