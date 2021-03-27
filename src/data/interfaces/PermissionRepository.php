<?php

namespace App\data\interfaces;

use App\domain\model\Permission\Permission;
use App\domain\model\Permission\AddPermissionModel;

interface PermissionRepository {
  public function add(AddPermissionModel $addPermissionModel) : Permission;
  public function get(int $permissionId) : Permission;
  public function delete(int $permissionId) : bool;
}