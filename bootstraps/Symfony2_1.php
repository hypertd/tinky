<?php

use Symfony\Bundle\FrameworkBundle\Console\Application;

date_default_timezone_set('Europe/London');
set_time_limit(0);

require __DIR__.'../../../app/bootstrap.php.cache';
require __DIR__.'../../../app/AppKernel.php';

$kernel = new \AppKernel('dev', true);
$kernel->loadClassCache();
$kernel->boot();

$container = $kernel->getContainer();
$application = new Application($kernel);
$application->setAutoExit(false);
