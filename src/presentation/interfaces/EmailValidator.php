<?php

namespace App\presentation\interfaces;
interface EmailValidator {
  public function isValid(String $email): bool;
}