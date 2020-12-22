<?php

use PHPUnit\Framework\TestCase;

final class DbToolTest extends TestCase
{
  private Faker\Generator $faker;
  private Tool $tool;
  private ToolRepository $toolRepository;
  private ModuleRepository $moduleRepository;
  private AddToolModel $addToolModel;

  protected function setUp() : void
  {
    $this->faker = Faker\Factory::create();
    $module = new Module($this->faker->randomDigit(), $this->faker->name());
    $this->tool = new Tool($this->faker->randomDigit(), $this->faker->name(), $module);
    $this->addToolModel = new AddToolModel($this->faker->name(), $module->id);
  }

  private function mockToolRepositorySuccess() {
    $mock = $this->createMock('ToolRepository');
    $mock
      ->method('add')
      ->with($this->addToolModel)
      ->willReturn($this->tool);
    $this->toolRepository = $mock;
  }

  private function mockToolRepositoryThrows() {
    $mock = $this->createMock('ToolRepository');
    $mock->expects($this->once())
        ->method('add')
        ->willThrowException(new Exception('any error tool'));
    $this->toolRepository = $mock;
  }

  private function mockModuleRepositorySuccess() {
    $mock = $this->createMock('ModuleRepository');
    $mock
      ->expects($this->once())
      ->method('exists')
      ->with('id', $this->tool->module->id)
      ->willReturn(true);
    $this->moduleRepository = $mock;
  }

  private function mockModuleRepositoryThrows() {
    $mock = $this->createMock('ModuleRepository');
    $mock
      ->method('exists')
      ->willThrowException(new Exception('any error module'));
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

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('any error tool');
    $sut->add($this->addToolModel);
  }


  public function testShouldThrowIfModuleRepositoryThrows(): void
  {
    $this->mockToolRepositorySuccess();
    $this->mockModuleRepositoryThrows();

    $sut = new DbTool($this->toolRepository, $this->moduleRepository);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('any error module');
    $sut->add($this->addToolModel);
  }
}