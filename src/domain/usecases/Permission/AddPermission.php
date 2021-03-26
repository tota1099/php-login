<?php

namespace App\domain\usecases\Permission;

use App\domain\model\Permission\AddPermissionModel;
use App\domain\model\Permission\Permission;

interface AddPermission {
  public function add(AddPermissionModel $permissionModel): Permission;
}