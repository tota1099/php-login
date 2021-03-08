<?php

namespace App\data\interfaces;

use App\domain\model\Account\Account;
use App\domain\model\Account\AddAccountModel;

interface AccountRepository {
  public function add(AddAccountModel $addAccountModel) : Account;
}