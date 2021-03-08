<?php

namespace App\infra\cryptografy;

use App\data\interfaces\Encrypter;

class BcryptAdapter implements Encrypter {
  public function encrypt(String $value): String
  {
    return password_hash($value, PASSWORD_BCRYPT);    
  }
}