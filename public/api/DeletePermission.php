<?php

require_once(__DIR__ . '/../../config.php');

use App\main\helpers\RouteHelper;
use App\main\factories\controllers\DeletePermissionFactory;

RouteHelper::route(DeletePermissionFactory::build());