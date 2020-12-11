<?php

interface ModuleRepository {
  public function add(AddModuleModel $addModuleModel) : Module;
}