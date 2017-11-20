<?php
//psysh loader
if(file_exists(__DIR__ . '/../vendor/autoload.php')){
    $psyLoader = require_once __DIR__ . '/../vendor/autoload.php';
}
else{
    echo 'Please run composer install';
    exit;
}

require 'Tinky.php';
$tinky = new Tinky();
$tinky->shell();