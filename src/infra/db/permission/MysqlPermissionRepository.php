<?php

namespace App\infra\db\permission;

use App\data\interfaces\AccountRepository;
use App\data\interfaces\PermissionRepository;
use App\data\interfaces\ToolRepository;
use App\domain\errors\DomainError;
use App\domain\model\Permission\AddPermissionModel;
use App\domain\model\Permission\Permission;
use App\infra\db\helpers\MysqlHelper;

class MysqlPermissionRepository implements PermissionRepository {

  private ToolRepository $toolRepository;
  private AccountRepository $accountRepository;

  public function __construct(ToolRepository $toolRepository, AccountRepository $accountRepository)
  {
    $this->toolRepository = $toolRepository;
    $this->accountRepository = $accountRepository;
  }

  public function get(int $permissionId) : Permission {
    $mysqlHelper = new MysqlHelper();
    $sql = "SELECT id, accountId, toolId, type FROM permission WHERE id = ?";

    $permission = $mysqlHelper->fetch($sql, [
      $permissionId
    ]);

    return new Permission(
      $permissionId,
      $this->accountRepository->get($permission['accountId']),
      $this->toolRepository->get($permission['toolId']),
      $permission['type']
    );
  }

  public function add(AddPermissionModel $addPermissionModel) : Permission {
    $mysqlHelper = new MysqlHelper();

    if($this->exists($addPermissionModel->accountId, $addPermissionModel->toolId, $addPermissionModel->type)) {
      throw new DomainError('Duplicate entry');
    }

    $sql = "INSERT INTO permission (accountId, toolId, type, created) VALUES (?,?,?,?)";
    
    $permissionId = $mysqlHelper->insert($sql, [
      $addPermissionModel->accountId,
      $addPermissionModel->toolId,
      $addPermissionModel->type,
      (new \DateTime())->format('Y-m-d H:i:s')
    ]);

    return new Permission(
      $permissionId,
      $this->accountRepository->get($addPermissionModel->accountId),
      $this->toolRepository->get($addPermissionModel->toolId),
      $addPermissionModel->type
    );
  }

  public function exists(int $accountId, int $toolId, int $type) : bool {
    $sql = "SELECT COUNT(*) FROM permission WHERE accountId = ? AND toolId = ? AND type = ?";

    $mysqlHelper = new MysqlHelper();
    return $mysqlHelper->exists($sql, [ $accountId, $toolId, $type ]);
  }
}