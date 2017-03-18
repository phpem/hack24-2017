<?php

use Controllers\IndexController;

$app->get('/', IndexController::class . ':index');