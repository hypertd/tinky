<?php

$config = new \Psy\Configuration();
$config->setDefaultIncludes(array(__DIR__.'/Bootstraps/Symfony2_1.php', __DIR__.'/Whiteboard.php'));

$shell = new \Psy\Shell($config);
$shell->run();
