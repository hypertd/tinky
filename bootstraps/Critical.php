<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once __DIR__.'/../../vendor/autoload.php';

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;

$config = Setup::createConfiguration($isDevMode);

$driver = new AnnotationDriver(new AnnotationReader(), [ __DIR__.'/../../src' ]);
AnnotationRegistry::registerLoader('class_exists');

$config->setMetadataDriverImpl($driver);

$localConfig = require(__DIR__.'/../../config/autoload/local.php');

$connectionParameters = [
    'driver' => 'pdo_pgsql',
    'host' => $localConfig['doctrine']['connection']['orm_default']['params']['host'],
    'port' => $localConfig['doctrine']['connection']['orm_default']['params']['port'],
    'dbname' => $localConfig['doctrine']['connection']['orm_default']['params']['dbname'],
    'user' => $localConfig['doctrine']['connection']['orm_default']['params']['user'],
    'password' => $localConfig['doctrine']['connection']['orm_default']['params']['password'],
];

$entityManager = EntityManager::create($connectionParameters, $config);