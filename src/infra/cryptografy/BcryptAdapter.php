<?php

namespace infra\cryptografy;

use data\interfaces\Encrypter;

class BcryptAdapter implements Encrypter {
  public function encrypt(String $value): String
  {
    return password_hash($value, PASSWORD_BCRYPT);    
  }
}