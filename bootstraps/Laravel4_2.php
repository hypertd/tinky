<?php

date_default_timezone_set('Europe/London');
set_time_limit(0);

require __DIR__.'/../../bootstrap/autoload.php';
$app = require_once __DIR__.'/../../bootstrap/start.php';

$app->setRequestForConsoleEnvironment();
$app->boot();
