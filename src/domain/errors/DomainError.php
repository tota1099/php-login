<?php

namespace App\domain\errors;

class DomainError extends \Exception {
  public function __construct($message) {
    parent::__construct($message);
  }
}