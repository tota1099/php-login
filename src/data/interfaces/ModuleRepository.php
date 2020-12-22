<?php

interface ModuleRepository {
  public function add(AddModuleModel $addModuleModel) : Module;
  public function exists(String $field, String $value) : bool;
}