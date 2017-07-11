<?php

require __DIR__ . '/../lib/Util.php';

($argc == 2) or die("Usage: php play.php <duration_seconds>\n");

$duration = $argv[1];

Play::killAllSounds();
Play::playSound($duration);
