<?php

$bootConfig = (include __DIR__.'/Config.php');

//required framework
$defaultIncludes = [
    __DIR__.'/Bootstraps/'.$bootConfig['bootstrapFile']
];

//preset tools
foreach (glob("tools/*.php") as $filename){
    $defaultIncludes[] = $filename;
}

//the whiteboard
$defaultIncludes[] = __DIR__.'/Whiteboard.php';

$config = new \Psy\Configuration();
$config->setDefaultIncludes($defaultIncludes);

$shell = new \Psy\Shell($config);
$shell->run();