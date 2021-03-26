<?php

namespace App\domain\model\Permission;

class AddPermissionModel {

  public function __construct(
    public int $accountId,
    public int $toolId,
    public String $type
  )
  {}
}