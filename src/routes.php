<?php

use App\Controllers\IndexController;

$app->get('/', IndexController::class . ':index');

$app->post('/topic', IndexController::class . ':topic');