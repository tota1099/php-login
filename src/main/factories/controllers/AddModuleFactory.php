<?php

class AddModuleFactory {
  public static function build() : Controller {
    $moduleRepository = new MysqlModuleRepository();
    $addModule = new DbModule($moduleRepository);
    return new AddModuleController($addModule);
  }
}