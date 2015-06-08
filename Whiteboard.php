<?php


Sentry::authenticate(['email' => 'admin@email.com', 'password' => 'admin']);

$w = App::make('WorkSessionService');
$d = App::make('DistributionService');
$c = App::make('CostsService');
$u = App::make('UnitsService');
$us = App::make('UserSettingsService');
$cl = App::make('ClientsService');
$cc = App::make('ClientsController');
$as = App::make('ActualsService');
$at = App::make('AttributesService');
$ss = App::make('StatementsService');

$ws = $w->getCurrent();
