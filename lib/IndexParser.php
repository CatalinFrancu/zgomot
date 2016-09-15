<?

class IndexParser {
  static $INDEX_DIR;

  static function init() {
    self::$INDEX_DIR = realpath(__DIR__ . '/../sound-index/');
  }

  static function extract($h1, $h2, $weekdays, $weekends, $page) {
    $data = [];

    $dayType = [];
    if ($weekdays) {
      $dayType[] = 'wd';
    }
    if ($weekends) {
      $dayType[] = 'we';
    }

    foreach ($dayType as $dt) {
      for ($h = $h1; $h < $h2; $h++) {
        $fileName = sprintf("%s/%s%02d.txt", self::$INDEX_DIR, $dt, $h);
        foreach (file($fileName) as $line) {
          list ($ts, $duration) = preg_split('/\s+/', trim($line));
          
          $data[] = [
            'ts' => $ts,
            'duration' => $duration,
          ];
        }
      }
    }

    // sort the data
    usort($data, function($a, $b) {
      return $a['ts'] < $b['ts'];
    });

    // extract a page
    $data = array_slice($data, $page * Util::PAGE_SIZE, Util::PAGE_SIZE);

    foreach ($data as &$r) {
      $year = strftime('%Y', $r['ts']);
      $month = strftime('%m', $r['ts']);
      $day = strftime('%d', $r['ts']);
      $hour = strftime('%H', $r['ts']);
      $minute = strftime('%M', $r['ts']);
      $second = strftime('%S', $r['ts']);

      $r['file'] = "clip/$year/$month/$day/$year-$month-$day-$hour-$minute-$second.mp3";
      $r['date'] = strftime('%A, %e %B %Y, %H:%M:%S', $r['ts']);
    }

    return $data;
  }
}

IndexParser::init();
