<?php

namespace App\infra\db\module;

use App\data\interfaces\ModuleRepository;
use App\domain\errors\DomainError;
use App\domain\model\Module\AddModuleModel;
use App\domain\model\Module\Module;
use App\infra\db\helpers\MysqlHelper;

class MysqlModuleRepository implements ModuleRepository {

  public function get(int $moduleId) : Module {
    $mysqlHelper = new MysqlHelper();
    $sql = "SELECT id, name, created FROM module WHERE id = ?";

    $module = $mysqlHelper->fetch($sql, [
      $moduleId
    ]);

    return new Module(
      $moduleId,
      $module['name']
    );
  }

  public function add(AddModuleModel $addModuleModel) : Module {
    $mysqlHelper = new MysqlHelper();

    if($this->exists('name', $addModuleModel->name)) {
      throw new DomainError('Duplicate entry');
    }

    $sql = "INSERT INTO module (name, created) VALUES (?,?)";
    $moduleId = $mysqlHelper->insert($sql, [
      $addModuleModel->name,
      (new \DateTime())->format('Y-m-d H:i:s')
    ]);

    return new Module(
      $moduleId,
      $addModuleModel->name,
    );
  }

  public function exists(String $field, String $value) : bool {
    $sql = "SELECT COUNT(*) FROM module WHERE {$field} = ? ";
    $mysqlHelper = new MysqlHelper();
    return $mysqlHelper->exists($sql, [ $value ]);
  }
}