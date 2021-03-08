<?php

namespace App\data\interfaces;

use App\domain\model\Tool\AddToolModel;
use App\domain\model\Tool\Tool;

interface ToolRepository {
  public function add(AddToolModel $addToolModel) : Tool;
}