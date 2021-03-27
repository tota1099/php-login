<?php

namespace App\main\factories\controllers;

use App\data\usecases\Permission\DbPermission;
use App\infra\db\account\MysqlAccountRepository;
use App\infra\db\module\MysqlModuleRepository;
use App\infra\db\permission\MysqlPermissionRepository;
use App\infra\db\tool\MysqlToolRepository;
use App\presentation\controllers\DeletePermissionController;
use App\presentation\interfaces\Controller;

class DeletePermissionFactory {
  public static function build() : Controller {
    $moduleRepository = new MysqlModuleRepository();
    $toolRepository = new MysqlToolRepository($moduleRepository);
    $accountRepository = new MysqlAccountRepository();
    $mysqlPermissionRepository = new MysqlPermissionRepository($toolRepository, $accountRepository);
    $dbPermission = new DbPermission($mysqlPermissionRepository);
    return new DeletePermissionController($dbPermission);
  }
}