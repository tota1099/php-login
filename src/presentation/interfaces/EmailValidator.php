<?php

interface EmailValidator {
  public function isValid(String $email): bool;
}