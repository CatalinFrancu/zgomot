<?

require '../../lib/Util.php';

$h1 = Request::get('h1');
$h2 = Request::get('h2');
$d1 = Request::get('d1');
$d2 = Request::get('d2');
$day = Request::get('day', 0);
$amp = Request::get('amp', 1);
$page = Request::get('page');

$data = IndexParser::extract($h1, $h2, $d1, $d2, $day, $amp, $page);

header('Content-Type: application/json');
print json_encode($data);
