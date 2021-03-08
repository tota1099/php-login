<?php

use PHPUnit\Framework\TestCase;

final class MysqlToolRepositoryTest extends TestCase
{
  public ToolRepository $sut;
  public ModuleRepository $moduleRepository;
  private Faker\Generator $faker;

  public function setUp() : void {
    $this->moduleRepository = new MysqlModuleRepository();
    $this->sut = new MysqlToolRepository($this->moduleRepository);
    $this->faker = Faker\Factory::create();
  }

  public function testShouldReturnAToolOnSuccess() {
    $name = $this->faker->name();
    $addModuleModel = new AddModuleModel($name);
    $module = $this->moduleRepository->add($addModuleModel);

    $name = $this->faker->name();
    $addToolModel = new AddToolModel($name, $module->id);
    $tool = $this->sut->add($addToolModel);

    $this->assertIsInt($tool->id);
    $this->assertEquals($name, $tool->name);
    $this->assertEquals($module->id, $tool->module->id);
  }

  public function testShouldThownIfModuleNotExists() {
    $name = $this->faker->name();
    $addToolModel = new AddToolModel($name, $this->faker->randomNumber());

    $this->expectException(DomainError::class);
    $this->expectExceptionMessage('No record found');

    $this->sut->add($addToolModel);
  }
}