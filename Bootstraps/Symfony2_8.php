<?php
$loader = require '../app/autoload.php';

use Symfony\Bundle\FrameworkBundle\Console\Application;

date_default_timezone_set('Europe/London');
set_time_limit(0);




$kernel = new AppKernel('dev', true);
$kernel->boot();

$container = $kernel->getContainer();

$application = new Application($kernel);
$application->setAutoExit(false);


if ($container->has('profiler')){
    $container->get('profiler')->disable();
}