<?php

require_once(__DIR__ . '/../../config.php');

use App\main\factories\controllers\AddPermissionFactory;
use App\main\helpers\RouteHelper;

RouteHelper::route(AddPermissionFactory::build());