<?php

namespace App\data\usecases\Tool;

use App\data\interfaces\ModuleRepository;
use App\data\interfaces\ToolRepository;
use App\domain\errors\DomainError;
use App\domain\model\Tool\AddToolModel;
use App\domain\model\Tool\Tool;
use App\domain\usecases\Tool\AddTool;

class DbTool implements AddTool {
  
  public function __construct(
    private ToolRepository $toolRepository,
    private ModuleRepository $moduleRepository,
  ) {}

  public function add(AddToolModel $addToolModel) : Tool {
    if(!$this->moduleRepository->exists('id', $addToolModel->moduleId)) {
      throw new DomainError('Invalid Module');
    }
    return $this->toolRepository->add($addToolModel);
  }
}