<?php
$adaptersLoader = require __DIR__ . '/../vendor/autoload.php';

foreach (glob(__DIR__."/*.php") as $filename){
    if(basename(__FILE__, '.php') !== basename($filename, '.php')){
        require $filename;
    }
}


// load {class}
// use this {class}
// load {class}
