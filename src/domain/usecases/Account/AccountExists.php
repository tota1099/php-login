<?php

namespace App\domain\usecases\Account;

use App\domain\model\Account\AccountExistsByEmailAndPassword;

interface AccountExists {
  public function existsByUserAndPassword(AccountExistsByEmailAndPassword $AccountExistsByEmailAndPassword): Bool;
}