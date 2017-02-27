<?php

require '../lib/Util.php';

$h1 = Request::get('h1', Config::get('global.minHour'));
$h2 = Request::get('h2', Config::get('global.maxHour'));
$d1 = Request::get('d1', Config::get('global.startDate'));
$d2 = Request::get('d2', date('d-m-Y'));
$day = Request::get('day', 0);
$amp = Request::get('amp', 1);

$data = IndexParser::extract($h1, $h2, $d1, $d2, $day, $amp, 0);

Smart::assign('data', $data);
Smart::assign('h1', $h1);
Smart::assign('h2', $h2);
Smart::assign('d1', $d1);
Smart::assign('d2', $d2);
Smart::assign('day', $day);
Smart::assign('amp', $amp);
Smart::assign('local', Config::get('global.local') /* global.local, hehe */);
Smart::assign('pageSize', Util::PAGE_SIZE);
Smart::display('index.tpl');
