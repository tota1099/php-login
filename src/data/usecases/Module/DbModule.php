<?php

namespace App\data\usecases\Module;

use App\data\interfaces\ModuleRepository;
use App\domain\model\Module\AddModuleModel;
use App\domain\model\Module\Module;
use App\domain\usecases\Module\AddModule;

class DbModule implements AddModule {
  
  public function __construct(
    private ModuleRepository $moduleRepository
  ) {}

  public function add(AddModuleModel $addModuleModel) : Module {
    return $this->moduleRepository->add($addModuleModel);
  }
}