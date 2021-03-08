<?php

namespace tests\data\usecases\Tool;

use App\data\interfaces\ModuleRepository;
use App\data\interfaces\ToolRepository;
use App\data\usecases\Tool\DbTool;
use App\domain\errors\DomainError;
use App\domain\model\Module\Module;
use App\domain\model\Tool\AddToolModel;
use App\domain\model\Tool\Tool;
use PHPUnit\Framework\TestCase;

final class DbToolTest extends TestCase
{
  private \Faker\Generator $faker;
  private Tool $tool;
  private ToolRepository $toolRepository;
  private ModuleRepository $moduleRepository;
  private AddToolModel $addToolModel;

  protected function setUp() : void
  {
    $this->faker = \Faker\Factory::create();
    $module = new Module($this->faker->randomDigit(), $this->faker->name());
    $this->tool = new Tool($this->faker->randomDigit(), $this->faker->name(), $module);
    $this->addToolModel = new AddToolModel($this->faker->name(), $module->id);
  }

  private function mockToolRepositorySuccess() {
    $mock = $this->createMock('App\data\interfaces\ToolRepository');
    $mock
      ->method('add')
      ->with($this->addToolModel)
      ->willReturn($this->tool);
    $this->toolRepository = $mock;
  }

  private function mockToolRepositoryThrows() {
    $mock = $this->createMock('App\data\interfaces\ToolRepository');
    $mock->expects($this->once())
        ->method('add')
        ->willThrowException(new \Exception('any error tool'));
    $this->toolRepository = $mock;
  }

  private function mockModuleRepositorySuccess(int $moduleId = 0) {
    $mock = $this->createMock('App\data\interfaces\ModuleRepository');
    $mock
      ->expects($this->once())
      ->method('exists')
      ->with('id', $moduleId ?: $this->tool->module->id)
      ->willReturn(true);
    $this->moduleRepository = $mock;
  }

  private function mockModuleRepositoryThrows() {
    $mock = $this->createMock('App\data\interfaces\ModuleRepository');
    $mock
      ->method('exists')
      ->willThrowException(new \Exception('any error module'));
    $this->moduleRepository = $mock;
  }

  public function testShouldCallToolRepositoryWithCorrectValues(): void
  {
    $this->mockToolRepositorySuccess();
    $this->mockModuleRepositorySuccess();
   
    $sut = new DbTool($this->toolRepository, $this->moduleRepository);

    $sut->add($this->addToolModel);
  }

  public function testShouldReturnToolOnSuccess(): void
  {
    $this->mockToolRepositorySuccess();
    $this->mockModuleRepositorySuccess();

    $sut = new DbTool($this->toolRepository, $this->moduleRepository);

    $this->assertSame($this->tool, $sut->add($this->addToolModel));
  }

  public function testShouldThrowIfToolRepositoryThrows(): void
  {
    $this->mockToolRepositoryThrows();
    $this->mockModuleRepositorySuccess();

    $sut = new DbTool($this->toolRepository, $this->moduleRepository);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('any error tool');
    $sut->add($this->addToolModel);
  }

  public function testShouldThrowIfModuleRepositoryThrows(): void
  {
    $this->mockToolRepositorySuccess();
    $this->mockModuleRepositoryThrows();

    $sut = new DbTool($this->toolRepository, $this->moduleRepository);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('any error module');
    $sut->add($this->addToolModel);
  }

  public function testShouldReturnDomainErrorIfModuleNotExists(): void
  {
    $this->mockToolRepositorySuccess();

    $mockModuleRepository = $this->createMock('App\data\interfaces\ModuleRepository');
    $mockModuleRepository
      ->expects($this->once())
      ->method('exists')
      ->with('id', $this->addToolModel->moduleId)
      ->willReturn(false);

    $sut = new DbTool($this->toolRepository, $mockModuleRepository);

    $this->expectException(DomainError::class);
    $this->expectExceptionMessage('Invalid Module');
    $sut->add($this->addToolModel);
  }
}