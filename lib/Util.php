<?php

class Util {
  const PAGE_SIZE = 100;
  const AMPLITUDE_CUTOFF = 80;

  static function autoloadLibClass($className) {
    $filename = __DIR__ . "/{$className}.php";
    if (file_exists($filename)) {
      require_once($filename);
    }
  }

  static function init() {
    setlocale(LC_ALL, 'ro_RO.utf8');
    spl_autoload_register('Util::autoloadLibClass');
    if (!self::isAjax()) {
      FlashMessage::restoreFromSession();
    }
  }

  static function redirect($location) {
    FlashMessage::saveToSession();
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $location");
    exit;
  }

  static function isAjax() {
    return isset($_SERVER['REQUEST_URI']) &&
      (strpos($_SERVER['REQUEST_URI'], '/ajax/') !== false);
  }

}

Util::init();
