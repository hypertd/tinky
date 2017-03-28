<?php

$bootConfig = (include __DIR__.'/Config.php');

$config = new \Psy\Configuration();
$config->setDefaultIncludes(array(__DIR__.'/Bootstraps/'.$bootConfig['bootstrapFile'], __DIR__.'/Whiteboard.php'));

$shell = new \Psy\Shell($config);
$shell->run();
