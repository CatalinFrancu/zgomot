<?php

require '../lib/Util.php';
require '../lib/Config.php';
require '../lib/smarty/Smarty.class.php';

$h1 = Request::get('h1', Config::get('global.minHour'));
$h2 = Request::get('h2', Config::get('global.maxHour'));
$d1 = Request::get('d1', Config::get('global.startDate'));
$d2 = Request::get('d2', date('d-m-Y'));
$day = Request::get('day', 0);
$amp = Request::get('amp', 1);

$data = IndexParser::extract($h1, $h2, $d1, $d2, $day, $amp, 0);

$smarty = new Smarty();
$smarty->template_dir = '../templates';
$smarty->compile_dir = '../templates_c';
$smarty->assign('data', $data);
$smarty->assign('h1', $h1);
$smarty->assign('h2', $h2);
$smarty->assign('d1', $d1);
$smarty->assign('d2', $d2);
$smarty->assign('day', $day);
$smarty->assign('amp', $amp);
$smarty->assign('local', Config::get('global.local') /* global.local, hehe */);
$smarty->assign('pageSize', Util::PAGE_SIZE);
$smarty->display('index.tpl');
