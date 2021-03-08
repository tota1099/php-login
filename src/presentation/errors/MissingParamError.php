<?php

namespace App\presentation\errors;

class MissingParamError extends \Exception {
  public function __construct($paramName) {
    parent::__construct('Missing param: ' . $paramName);
  }
}