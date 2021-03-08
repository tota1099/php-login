<?php

require_once(__DIR__ . '/../../config.php');

use App\main\factories\controllers\AddToolFactory;
use App\main\helpers\RouteHelper;

RouteHelper::route(AddToolFactory::build());