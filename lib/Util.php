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

  // converts a number of seconds to HH:MM:SS
  static function secondsToTime($s) {
    return sprintf('%02d:%02d:%02d', $s / 3600, $s / 60 % 60, $s % 60);
  }

  // $cidr has the form %d.%d.%d.%d/%d
  static function inRange($ip, $cidr) {
    list ($subnet, $bits) = explode('/', $cidr);
    $mask = -1 << (32 - $bits); // 11...1100..00, with $bits 1's and 32 - $bits 0's

    $ip = ip2long($ip);
    $subnet = ip2long($subnet);
    $subnet &= $mask;  // make sure subnet has 0's at the end
    return ($ip & $mask) == $subnet;
  }

  // $validList contains IPs and/or CIDR notations, separated by commas or whitespace.
  static function validIp($ip, $validList) {
    $list = preg_split('/[, \t]+/', $validList);
    foreach ($list as $spec) {
      if (strpos($spec, '/') === false) {
        $spec .= '/32';
      }
      if (self::inRange($ip, $spec)) {
        return true;
      }
    }

    return false;
  }
}

Util::init();
