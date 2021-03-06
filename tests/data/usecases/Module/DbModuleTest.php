<?php

namespace App\Test\data\usecases\Module;

use App\data\interfaces\ModuleRepository;
use App\data\usecases\Module\DbModule;
use App\domain\model\Module\AddModuleModel;
use App\domain\model\Module\Module;
use PHPUnit\Framework\TestCase;

final class DbModuleTest extends TestCase
{
  private \Faker\Generator $faker;
  private Module $module;
  private ModuleRepository $moduleRepository;
  private AddModuleModel $addModuleModel;

  protected function setUp() : void
  {
    $this->faker = \Faker\Factory::create();
    $this->module = new Module($this->faker->randomDigit(), $this->faker->name());
    $this->addModuleModel = new AddModuleModel($this->faker->name());
  }

  private function mockModuleRepositorySuccess() {
    $mock = $this->createMock('App\data\interfaces\ModuleRepository');
    $mock
      ->expects($this->once())
      ->method('add')
      ->with($this->addModuleModel)
      ->willReturn($this->module);
    $this->moduleRepository = $mock;
  }

  private function mockModuleRepositoryThrows() {
    $mock = $this->createMock('App\data\interfaces\ModuleRepository');
    $mock->expects($this->once())
        ->method('add')
        ->willThrowException(new \Exception('any error'));
    $this->moduleRepository = $mock;
  }

  public function testShouldCallModuleRepositoryWithCorrectValues(): void
  {
    $this->mockModuleRepositorySuccess();
   
    $sut = new DbModule($this->moduleRepository);

    $sut->add($this->addModuleModel);
  }

  public function testShouldReturnModuleOnSuccess(): void
  {
    $this->mockModuleRepositorySuccess();

    $sut = new DbModule($this->moduleRepository);

    $this->assertSame($this->module, $sut->add($this->addModuleModel));
  }

  public function testShouldThrowIfModuleRepositoryThrows(): void
  {
    $this->mockModuleRepositoryThrows();

    $sut = new DbModule($this->moduleRepository);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('any error');
    $sut->add($this->addModuleModel);
  }
}