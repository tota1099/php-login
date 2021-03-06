<?php

namespace App\domain\model\Tool;

use App\domain\model\Module\Module;

class Tool {

  public function __construct(
    public int $id,
    public string $name,
    public Module $module
  )
  {}
}