<?php
use App\Controllers\HomeController;
use App\Controllers\TwitterServiceController;

$app->get('/', HomeController::class . ':index');
$app->get('/resources/twitter', TwitterServiceController::class . ':getTwitterResource');