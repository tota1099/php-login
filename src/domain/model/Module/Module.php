<?php

namespace App\domain\model\Module;
class Module {

  public function __construct(
    public int $id,
    public string $name,
  )
  {}
}