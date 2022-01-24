<?php

require_once(__DIR__ . '/../../config.php');

use App\main\factories\controllers\SignInFactory;
use App\main\helpers\RouteHelper;

RouteHelper::route(SignInFactory::build());