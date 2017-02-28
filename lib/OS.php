<?php

class OS {

  static function executeAndAssert($command) {
    $exitCode = 0;
    $output = null;
    exec($command, $output, $exitCode);
    if ($exitCode) {
      var_dump("Failed command: $command (code $exitCode)");
      exit($exitCode);
    }
  }

  static function executeAndReturnOutput($command) {
    $exitCode = 0;
    $output = null;
    exec($command, $output, $exitCode);
    if ($exitCode) {
      print("ERROR: Failed command: $command (code $exitCode)\n");
      var_dump($output);
      exit;
    }
    return $output;
  }

}
