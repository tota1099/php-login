<?php

require_once(__DIR__ . '/../../config.php');

use App\main\factories\controllers\SignUpFactory;
use App\main\helpers\RouteHelper;

RouteHelper::route(SignUpFactory::build());