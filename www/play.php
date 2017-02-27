<?php

require '../lib/Util.php';
require '../lib/Config.php';
require '../lib/smarty/Smarty.class.php';

$durationIndex = Request::get('durationIndex');

if ($durationIndex != null) {
  $durations = Config::get('playServer.duration');
  $duration = $durations[$durationIndex];

  $endTimestamp = time() + $duration;
  $fileName = Config::get('playServer.watchFile');
  file_put_contents($fileName, $endTimestamp);

  Util::redirect('play.php');
}

$smarty = new Smarty();
$smarty->template_dir = '../templates';
$smarty->compile_dir = '../templates_c';
$smarty->assign('local', false);
$smarty->assign('durations', Config::get('playServer.duration'));
$smarty->display('play.tpl');
