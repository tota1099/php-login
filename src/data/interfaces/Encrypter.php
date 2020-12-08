<?php

interface Encrypter {
  public function encrypt(String $value): String;
}