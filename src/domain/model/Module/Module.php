<?php

class Module {

  public function __construct(
    public int $id,
    public string $name,
  )
  {}
}

class AddModuleModel {

  public function __construct(
    public string $name
  )
  {}
}