<?php
if(file_exists(__DIR__ . '/../vendor/autoload.php')){
    $adaptersLoader = require_once __DIR__ . '/../vendor/autoload.php';
    
    foreach (glob(__DIR__."/*.php") as $filename){
        if(basename(__FILE__, '.php') !== basename($filename, '.php')){
            require $filename;
        }
    }
}
else{
   echo "No tools loaded.\n\n"; 
}


// load {class}
// use this {class}
// load {class}
