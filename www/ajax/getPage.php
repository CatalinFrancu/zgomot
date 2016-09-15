<?

require '../../lib/Util.php';

$minHour = Request::get('minHour');
$maxHour = Request::get('maxHour');
$startDate = Request::get('startDate');
$endDate = Request::get('endDate');
$weekdays = Request::isset('weekdays');
$weekends = Request::isset('weekends');
$page = Request::get('page');

$data = IndexParser::extract($minHour, $maxHour, $startDate, $endDate, $weekdays, $weekends, $page);

header('Content-Type: application/json');
print json_encode($data);
