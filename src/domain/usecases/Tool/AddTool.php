<?php

namespace App\domain\usecases\Tool;

use App\domain\model\Tool\AddToolModel;
use App\domain\model\Tool\Tool;

interface AddTool {
  public function add(AddToolModel $toolModel): Tool;
}