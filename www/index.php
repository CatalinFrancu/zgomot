<?

$MIN_HOUR = 1;
$MAX_HOUR = 8;

require '../lib/Util.php';
require '../lib/IndexParser.php';
require '../lib/Request.php';
require '../lib/smarty/Smarty.class.php';

$minHour = Request::get('h1', $MIN_HOUR);
$maxHour = Request::get('h2', $MAX_HOUR);
$weekdays = Request::get('wd', 1);
$weekends = Request::get('we', 1);

$data = IndexParser::extract($minHour, $maxHour, $weekdays, $weekends, 0);

$smarty = new Smarty();
$smarty->template_dir = '../templates';
$smarty->compile_dir = '../templates_c';
$smarty->assign('data', $data);
$smarty->assign('minHour', $minHour);
$smarty->assign('maxHour', $maxHour);
$smarty->assign('weekdays', $weekdays);
$smarty->assign('weekends', $weekends);
$smarty->assign('pageSize', Util::PAGE_SIZE);
$smarty->display('index.tpl');
