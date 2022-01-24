<?php

namespace App\domain\model\Account;

class AccountExistsByEmailAndPassword {

  public function __construct(
    public String $email,
    public String $password
  )
  {}
}