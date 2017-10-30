<?php
use Symfony\Bundle\FrameworkBundle\Console\Application;

date_default_timezone_set('Europe/London');
set_time_limit(0);

$loader = require '../app/autoload.php';

$kernel = new AppKernel('dev', true);
$kernel->boot();

$container = $kernel->getContainer();

$application = new Application($kernel);
$application->setAutoExit(false);