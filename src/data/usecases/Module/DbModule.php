<?php

class DbModule implements AddModule {
  
  public function __construct(
    private ModuleRepository $moduleRepository
  ) {}

  public function add(AddModuleModel $addModuleModel) : Module {
    return $this->moduleRepository->add($addModuleModel);
  }
}