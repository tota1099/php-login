<?php

namespace App\utils;

use App\presentation\interfaces\EmailValidator;

class FilterEmailValidator implements EmailValidator {
  public function isValid(String $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }
}