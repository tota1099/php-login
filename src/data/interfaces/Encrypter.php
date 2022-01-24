<?php

namespace App\data\interfaces;
interface Encrypter {
  public function encrypt(String $value): String;
  public function checkEncrypt(String $value, String $hash): bool;
}