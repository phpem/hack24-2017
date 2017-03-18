<?php

use App\Controllers\IndexController;

$app->get('/', IndexController::class . ':index');