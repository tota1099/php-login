<?php

use PHPUnit\Framework\TestCase;

final class MysqlModuleRepositoryTest extends TestCase
{
  public MysqlModuleRepository $sut;
  private Faker\Generator $faker;

  public function setUp() : void {
    $this->sut = new MysqlModuleRepository();
    $this->faker = Faker\Factory::create();
  }

  public function testShouldReturnAModuleOnSuccess() {
    $name = $this->faker->name();
    $addModuleModel = new AddModuleModel($name);
    $module = $this->sut->add($addModuleModel);

    $this->assertIsInt($module->id);
    $this->assertEquals($name, $module->name);
  }
}