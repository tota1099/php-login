<?php

namespace App\domain\usecases\Account;

use App\domain\model\Account\Account;
use App\domain\model\Account\AddAccountModel;

interface AddAccount {
  public function add(AddAccountModel $accountModel): Account;
}