<?php

interface AddModule {
  public function add(AddModuleModel $moduleModel): Module;
}