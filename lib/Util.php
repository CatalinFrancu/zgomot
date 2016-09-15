<?

class Util {
  const PAGE_SIZE = 100;

  static function init() {
    setlocale(LC_ALL, 'ro_RO.utf8');
    require_once 'IndexParser.php';
    require_once 'Request.php';
  }
}

Util::init();
