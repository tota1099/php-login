<?php

class MysqlModuleRepository implements ModuleRepository {

  public function add(AddModuleModel $addModuleModel) : Module {
    $mysqlHelper = new MysqlHelper();

    if($this->exists('name', $addModuleModel->name)) {
      throw new DomainError('Duplicate entry');
    }

    $sql = "INSERT INTO module (name, created) VALUES (?,?)";
    $moduleId = $mysqlHelper->insert($sql, [
      $addModuleModel->name,
      (new DateTime())->format('Y-m-d H:i:s')
    ]);

    return new Module(
      $moduleId,
      $addModuleModel->name,
    );
  }

  private function exists(String $field, String $value) : bool {
    $sql = "SELECT COUNT(*) FROM account WHERE {$field} = ? ";
    $mysqlHelper = new MysqlHelper();
    return $mysqlHelper->exists($sql, [ $value ]);
  }
}