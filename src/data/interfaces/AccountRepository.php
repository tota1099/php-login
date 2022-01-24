<?php

namespace App\data\interfaces;

use App\domain\model\Account\Account;
use App\domain\model\Account\AddAccountModel;

interface AccountRepository {
  public function add(AddAccountModel $addAccountModel) : Account;
  public function get(int $accountId) : Account;
  public function getByEmail(String $email) : Account;
  public function existsByEmail(String $email) : bool;
}