<?php

function isCli(){
    return php_sapi_name() === 'cli';
}

function __tools(){
    $toolsDir = __DIR__ . '/../workbench/tools';
    if(file_exists($toolsDir .'/vendor/autoload.php')){
        require $toolsDir. '/vendor/autoload.php';
    
        /*foreach (glob(__DIR__."/*.php") as $filename){
            if(basename(__FILE__, '.php') !== basename($filename, '.php')){
                require $filename;
            }
        }*/
        
        if(isCli())
            echo "Tools auto-loaded.\n\n"; 
    }
    else{
        if(isCli())
            echo "No tools loaded.\n\n"; 
    }
}