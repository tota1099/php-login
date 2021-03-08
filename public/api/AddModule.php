<?php

require_once(__DIR__ . '/../../config.php');

use App\main\factories\controllers\AddModuleFactory;
use App\main\helpers\RouteHelper;

RouteHelper::route(AddModuleFactory::build());