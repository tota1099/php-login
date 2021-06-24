<?php

namespace App\domain\model\Account;
class Account {

  public function __construct(
    public int $id,
    public string $name,
    public String $email,
    public String $created
  )
  {}
}
