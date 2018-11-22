<?php

//psysh loader
if(file_exists(__DIR__ . '/../vendor/autoload.php')){
    require __DIR__ . '/../vendor/autoload.php';
}
else{
    echo "Please run composer install\n";
    exit;
}

$tinky = new Tinky\Tinky();
$tinky->shell();