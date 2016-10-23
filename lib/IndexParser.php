<?

class IndexParser {
  static $INDEX_DIR;

  static function init() {
    self::$INDEX_DIR = realpath(__DIR__ . '/../sound-index/');
  }

  static function extract($h1, $h2, $d1, $d2, $day, $amp, $page) {
    $data = [];

    switch ($day) {
    case 0: $dayType = ['wd', 'we']; break;
    case 1: $dayType = ['wd']; break;
    case 2: $dayType = ['we']; break;
    }

    $startTs = self::dateToTimestamp($d1);
    $endTs = self::dateToTimestamp($d2) + 86400; // start of the next day

    foreach ($dayType as $dt) {
      for ($h = $h1; $h < $h2; $h++) {
        $fileName = sprintf("%s/%s%02d.txt", self::$INDEX_DIR, $dt, $h);
        foreach (file($fileName) as $line) {
          list ($ts, $duration, $amplitude) = preg_split('/\s+/', trim($line));

          $goodAmp = (($amp == 0) ||
                      (($amp == 1) && ($amplitude >= Util::AMPLITUDE_CUTOFF)) ||
                      (($amp == 2) && ($amplitude < Util::AMPLITUDE_CUTOFF)));

          if (($ts >= $startTs) && ($ts < $endTs) && $goodAmp) {
            $data[] = [
              'ts' => $ts,
              'duration' => $duration,
              // 'amplitude' => ($amplitude >= Util::AMPLITUDE_CUTOFF) ? 'mare' : 'mediu',
              'amplitude' => $amplitude,
            ];
          }
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

  // Converts a date in the DD-MM-YYYY format to a timestamp. Assumes the time is 00:00:00.
  static function dateToTimestamp($date) {
    return DateTime::createFromFormat('d-m-Y', $date)
      ->setTime(0, 0)
      ->getTimestamp();
  }
}

IndexParser::init();
