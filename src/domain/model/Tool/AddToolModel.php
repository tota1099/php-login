<?php

namespace App\domain\model\Tool;

class AddToolModel {

  public function __construct(
    public string $name,
    public int $moduleId
  )
  {}
}