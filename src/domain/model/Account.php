<?php

class Account {

  public function __construct(
    public int $id,
    public string $name,
    public String $email
  )
  {}
}

class AddAccountModel {

  public function __construct(
    public string $name,
    public String $email,
    public String $password
  )
  {}
}