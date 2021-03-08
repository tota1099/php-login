<?php

namespace App\main\factories\controllers;

use App\data\usecases\Tool\DbTool;
use App\infra\db\module\MysqlModuleRepository;
use App\infra\db\tool\MysqlToolRepository;
use App\presentation\controllers\AddToolController;
use App\presentation\interfaces\Controller;

class AddToolFactory {
  public static function build() : Controller {
    $moduleRepository = new MysqlModuleRepository();
    $toolRepository = new MysqlToolRepository($moduleRepository);
    $addTool = new DbTool($toolRepository, $moduleRepository);
    return new AddToolController($addTool);
  }
}