<?php

date_default_timezone_set('Europe/London');
set_time_limit(0);

require_once __DIR__.'../../../app/bootstrap.php.cache';
require_once __DIR__.'../../../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$kernel->boot();
