<?php

class Tool {

  public function __construct(
    public int $id,
    public string $name,
    public Module $module
  )
  {}
}

class AddToolModel {

  public function __construct(
    public string $name,
    public int $moduleId
  )
  {}
}