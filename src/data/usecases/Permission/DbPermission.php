<?php

namespace App\data\usecases\Permission;

use App\data\interfaces\PermissionRepository;
use App\domain\model\Permission\AddPermissionModel;
use App\domain\model\Permission\Permission;
use App\domain\usecases\Permission\AddPermission;
use App\domain\usecases\Permission\DeletePermission;

class DbPermission implements AddPermission, DeletePermission {
  
  public function __construct(
    private PermissionRepository $permissionRepository
  ) {}

  public function add(AddPermissionModel $addPermissionModel) : Permission {
    return $this->permissionRepository->add($addPermissionModel);
  }

  public function delete(int $deleteId) : bool {
    return $this->permissionRepository->delete($deleteId);
  }
}