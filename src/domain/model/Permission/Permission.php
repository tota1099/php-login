<?php

namespace App\domain\model\Permission;

use App\domain\model\Account\Account;
use App\domain\model\Tool\Tool;

class Permission {

  public function __construct(
    public int $id,
    public Account $account,
    public Tool $tool,
    public String $type
  )
  {}
}