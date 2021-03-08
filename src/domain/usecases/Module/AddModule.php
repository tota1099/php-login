<?php

namespace App\domain\usecases\Module;

use App\domain\model\Module\AddModuleModel;
use App\domain\model\Module\Module;

interface AddModule {
  public function add(AddModuleModel $moduleModel): Module;
}