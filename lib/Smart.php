<?php

/**
 * Wrapper around Smarty
 **/

require_once 'third-party/smarty-4.3.0/Smarty.class.php';

class Smart {
  private static $theSmarty = null;

  static function init() {
    self::$theSmarty = new Smarty();
    self::$theSmarty->template_dir = __DIR__ . '/../templates';
    self::$theSmarty->compile_dir = __DIR__ . '/../templates_c';
  }

  static function display($templateName) {
    self::assign('flashMessages', FlashMessage::getMessages());
    self::$theSmarty->display($templateName);
  }

  static function assign($variable, $value) {
    self::$theSmarty->assign($variable, $value);
  }

}

Smart::init();
