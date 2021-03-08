<?php

namespace App\domain\model\Account;

class AddAccountModel {

  public function __construct(
    public string $name,
    public String $email,
    public String $password
  )
  {}
}