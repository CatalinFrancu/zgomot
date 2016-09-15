<?php

/**
 * This class reads request parameters.
 **/
class Request {

  static function get($name, $default = null) {
    return array_key_exists($name, $_REQUEST)
      ? $_REQUEST[$name]
      : $default;
  }

  /* Reads a present-or-not parameter (checkbox, button etc.). */
  static function isset($name) {
    return array_key_exists($name, $_REQUEST);
  }
}

?>
