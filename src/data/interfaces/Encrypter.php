<?php

namespace data\interfaces;
interface Encrypter {
  public function encrypt(String $value): String;
}