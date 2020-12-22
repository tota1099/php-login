<?php

class DbTool implements AddTool {
  
  public function __construct(
    private ToolRepository $toolRepository,
    private ModuleRepository $moduleRepository,
  ) {}

  public function add(AddToolModel $addToolModel) : Tool {
    return $this->toolRepository->add($addToolModel);
  }
}