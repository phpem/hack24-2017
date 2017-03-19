<?php

use App\Controllers\IndexController;

$app->get('/', IndexController::class . ':index');

$app->get('/topic', IndexController::class . ':topic');
