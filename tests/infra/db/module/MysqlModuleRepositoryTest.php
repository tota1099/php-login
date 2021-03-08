<?php

namespace tests\infra\db\module;

use App\data\interfaces\ModuleRepository;
use App\domain\errors\DomainError;
use App\domain\model\Module\AddModuleModel;
use App\infra\db\module\MysqlModuleRepository;
use PHPUnit\Framework\TestCase;

final class MysqlModuleRepositoryTest extends TestCase
{
  public ModuleRepository $sut;
  private \Faker\Generator $faker;

  public function setUp() : void {
    $this->sut = new MysqlModuleRepository();
    $this->faker = \Faker\Factory::create();
  }

  public function testShouldReturnAModuleOnSuccess() {
    $name = $this->faker->name();
    $addModuleModel = new AddModuleModel($name);
    $module = $this->sut->add($addModuleModel);

    $this->assertIsInt($module->id);
    $this->assertEquals($name, $module->name);

    $modulePersist = $this->sut->get($module->id);

    $this->assertEquals($modulePersist->id, $module->id);
    $this->assertEquals($modulePersist->name, $module->name);
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