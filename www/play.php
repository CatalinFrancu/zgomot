<?php

require '../lib/Util.php';

$ip = $_SERVER['REMOTE_ADDR'];
if (!Util::validIp($ip, Config::get('playServer.validIps'))) {
  FlashMessage::add('Nu puteți accesa această pagină.');
  Util::redirect('index.php');
}

$durationIndex = Request::get('durationIndex');
$stop = Request::get('stop');

$fileName = Config::get('playServer.watchFile');

if ($stop) {
  file_put_contents($fileName, 0); // cause playback to stop

  FlashMessage::add('Am oprit sunetul.', 'warning');
  Util::redirect('play.php');
}

if ($durationIndex != null) {
  $durations = Config::get('playServer.duration');
  $duration = $durations[$durationIndex];

  $endTimestamp = time() + $duration;
  file_put_contents($fileName, $endTimestamp);

  FlashMessage::add(sprintf('Am pornit sunetul până la %s.',
                            date('H:i:s', $endTimestamp)),
                    'success');
  Util::redirect('play.php');
}

Smart::assign('local', false);
Smart::assign('durations', Config::get('playServer.duration'));
Smart::display('play.tpl');
