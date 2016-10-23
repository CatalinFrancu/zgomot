<?

require '../../lib/Util.php';

$minHour = Request::get('minHour');
$maxHour = Request::get('maxHour');
$startDate = Request::get('startDate');
$endDate = Request::get('endDate');
$weekdays = Request::isset('weekdays');
$weekends = Request::isset('weekends');
$ampHi = Request::get('ampHi', 1);
$ampMed = Request::get('ampMed', 1);
$page = Request::get('page');

$data = IndexParser::extract($minHour, $maxHour, $startDate, $endDate,
                             $weekdays, $weekends, $ampHi, $ampMed, $page);

header('Content-Type: application/json');
print json_encode($data);
