<?

require '../../lib/Util.php';
require '../../lib/IndexParser.php';

$minHour = $_REQUEST['minHour'];
$maxHour = $_REQUEST['maxHour'];
$weekdays = $_REQUEST['weekdays'] == 'true';
$weekends = $_REQUEST['weekends'] == 'true';
$page = $_REQUEST['page'];

$data = IndexParser::extract($minHour, $maxHour, $weekdays, $weekends, $page);

header('Content-Type: application/json');
print json_encode($data);
