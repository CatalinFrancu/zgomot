<?php

require '../lib/Util.php';

$ip = $_SERVER['REMOTE_ADDR'];
if (!Util::validIp($ip, Config::get('play.validIps'))) {
  FlashMessage::add('Nu puteți accesa această pagină.');
  Util::redirect('index.php');
}

$duration = Request::get('duration');
$stop = Request::get('stop');

if ($stop) {
  Play::killAllSounds();
  FlashMessage::add('Am oprit sunetul.', 'warning');
  Util::redirect('play.php');
}

if ($duration != null) {
  Play::playSound($duration);
  FlashMessage::add("Am pornit sunetul pentru {$duration} secunde.", 'success');
  Util::redirect('play.php');
}

Smart::assign('local', false);
Smart::assign('durations', Config::get('play.duration'));
Smart::display('play.tpl');
