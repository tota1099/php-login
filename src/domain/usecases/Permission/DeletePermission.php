<?php

namespace App\domain\usecases\Permission;

interface DeletePermission {
  public function delete(int $permissionId): bool;
}