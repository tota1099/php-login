<?php

namespace App\data\interfaces;
interface Encrypter {
  public function encrypt(String $value): String;
}