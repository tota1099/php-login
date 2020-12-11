<?php

use PHPUnit\Framework\TestCase;

final class AddModuleControllerTest extends TestCase
{
  private DbModule $dbModule;
  private Module $module;
  private Faker\Generator $faker;

  private function makeSut(): Controller {
    return new AddModuleController($this->dbModule);
  }

  private function mockSuccess(): void {
    $mock = $this->createMock('DbModule');
    $this->module = new Module($this->faker->randomDigit(), $this->faker->name());
    $mock->method('add')->with($this->module->name)->willReturn($this->module);
    $this->dbModule = $mock;
  }

  private function mockThrows(): void {
    $mock = $this->createMock('DbModule');
    $mock->expects($this->once())->method('add')->willThrowException(new Exception());
    $this->dbModule = $mock;
  }

  public function setUp() : void {
    $this->faker = Faker\Factory::create();
  }

  public function testShouldReturn400IfNoNameIsProvided() {  
    $this->mockSuccess();
    $sut = $this->makeSut();

    $httpRequest = new HttpRequest();
    $httpResponse = $sut->handle($httpRequest);

    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new MissingParamError('name'))->getMessage());
  }

  public function testShouldCallDbModuleWithValues() {
    $this->mockSuccess();
    $sut = $this->makeSut();

    $httpRequest = new HttpRequest(['name' => $this->module->name]);

    $sut->handle($httpRequest);
    $this->assertTrue(true);
  }

  public function testShouldReturn500IfDbModuleThrows() { 
    $this->mockThrows();
    $sut = $this->makeSut();

    $httpRequest = new HttpRequest(['name' => $this->faker->name]);

    $httpResponse = $sut->handle($httpRequest);
    $this->assertEquals($httpResponse->statusCode, 500);
  }
}