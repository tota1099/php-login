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

  public function testShouldThrowIfAlreadyExists() {
    $name = $this->faker->name();
    $addModuleModel = new AddModuleModel($name);
    $module = $this->sut->add($addModuleModel);

    $this->assertIsInt($module->id);
    $this->assertEquals($name, $module->name);

    $this->expectException(DomainError::class);
    $this->expectExceptionMessage('Duplicate entry');
    $this->sut->add($addModuleModel);
  }

  public function testShouldReturnTrueIfExists() {
    $name = $this->faker->name();
    $addModuleModel = new AddModuleModel($name);
    $module = $this->sut->add($addModuleModel);

    $this->assertTrue($this->sut->exists('id', $module->id));
    $this->assertTrue($this->sut->exists('name', $module->name));
  }

  public function testShouldReturnFalseIfExists() {
    $name = $this->faker->name();
    $addModuleModel = new AddModuleModel($name);
    $module = $this->sut->add($addModuleModel);

    $this->assertTrue($this->sut->exists('id', $module->id));
    $this->assertTrue($this->sut->exists('name', $module->name));

    $this->assertFalse($this->sut->exists('id', $module->id + 1));
    $this->assertFalse($this->sut->exists('name', $this->faker->name()));
  }
}