<?php

namespace App\main\factories\controllers;

use App\data\usecases\Module\DbModule;
use App\infra\db\module\MysqlModuleRepository;
use App\presentation\controllers\AddModuleController;
use App\presentation\interfaces\Controller;

class AddModuleFactory {
  public static function build() : Controller {
    $moduleRepository = new MysqlModuleRepository();
    $addModule = new DbModule($moduleRepository);
    return new AddModuleController($addModule);
  }
}