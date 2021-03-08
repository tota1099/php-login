<?php

namespace App\data\interfaces;

use App\domain\model\Module\AddModuleModel;
use App\domain\model\Module\Module;

interface ModuleRepository {
  public function add(AddModuleModel $addModuleModel) : Module;
  public function get(int $moduleId) : Module;
  public function exists(String $field, String $value) : bool;
}