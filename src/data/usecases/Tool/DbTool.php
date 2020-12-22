<?php

class DbTool implements AddTool {
  
  public function __construct(
    private ToolRepository $toolRepository,
    private ModuleRepository $moduleRepository,
  ) {}

  public function add(AddToolModel $addToolModel) : Tool {
    if(!$this->moduleRepository->exists('id', $addToolModel->moduleId)) {
      new DomainError('Invalid Module.');
    }
    return $this->toolRepository->add($addToolModel);
  }
}