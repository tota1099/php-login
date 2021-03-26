<?php

namespace App\infra\db\tool;

use App\data\interfaces\ModuleRepository;
use App\data\interfaces\ToolRepository;
use App\domain\errors\DomainError;
use App\domain\model\Tool\AddToolModel;
use App\domain\model\Tool\Tool;
use App\infra\db\helpers\MysqlHelper;

class MysqlToolRepository implements ToolRepository {

  private ModuleRepository $moduleRepository;

  public function __construct(ModuleRepository $moduleRepository)
  {
    $this->moduleRepository = $moduleRepository;
  }

  public function get(int $toolId) : Tool {
    $mysqlHelper = new MysqlHelper();
    $sql = "SELECT id, name, moduleId, created FROM tool WHERE id = ?";

    $tool = $mysqlHelper->fetch($sql, [
      $toolId
    ]);

    return new Tool(
      $toolId,
      $tool['name'],
      $this->moduleRepository->get($tool['moduleId'])
    );
  }


  public function add(AddToolModel $addToolModel) : Tool {
    $mysqlHelper = new MysqlHelper();

    if($this->exists('name', $addToolModel->name)) {
      throw new DomainError('Duplicate entry');
    }

    if(!$this->moduleRepository->exists('id', $addToolModel->moduleId)) {
      throw new DomainError('Invalid Module');
    }

    $sql = "INSERT INTO tool (name, moduleId, created) VALUES (?,?,?)";
    $toolId = $mysqlHelper->insert($sql, [
      $addToolModel->name,
      $addToolModel->moduleId,
      (new \DateTime())->format('Y-m-d H:i:s')
    ]);

    return new Tool(
      $toolId,
      $addToolModel->name,
      $this->moduleRepository->get($addToolModel->moduleId)
    );
  }

  public function exists(String $field, String $value) : bool {
    $sql = "SELECT COUNT(*) FROM tool WHERE {$field} = ? ";
    $mysqlHelper = new MysqlHelper();
    return $mysqlHelper->exists($sql, [ $value ]);
  }
}