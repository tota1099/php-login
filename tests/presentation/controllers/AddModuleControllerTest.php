<?php

namespace App\Test\presentation\controllers;

use App\data\usecases\Module\DbModule;
use App\domain\errors\DomainError;
use App\domain\model\Module\AddModuleModel;
use App\domain\model\Module\Module;
use PHPUnit\Framework\TestCase;
use App\presentation\controllers\AddModuleController;
use App\presentation\errors\MissingParamError;
use App\presentation\helpers\Ok;
use App\presentation\interfaces\Controller;
use App\presentation\interfaces\HttpRequest;

final class AddModuleControllerTest extends TestCase
{
  private DbModule $dbModule;
  private Module $module;
  private \Faker\Generator $faker;

  private function makeSut(): Controller {
    return new AddModuleController($this->dbModule);
  }

  private function mockSuccess(): void {
    $mock = $this->createMock('App\data\usecases\Module\DbModule');
    $this->module = new Module($this->faker->randomDigit(), $this->faker->name());
    $mock->method('add')->with(new AddModuleModel($this->module->name))->willReturn($this->module);
    $this->dbModule = $mock;
  }

  private function mockThrows(): void {
    $mock = $this->createMock('App\data\usecases\Module\DbModule');
    $mock->expects($this->once())->method('add')->willThrowException(new \Exception());
    $this->dbModule = $mock;
  }

  public function setUp() : void {
    $this->faker = \Faker\Factory::create();
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

  public function testShouldReturnModuleIfSuccess() {
    $this->mockSuccess();
    $sut = $this->makeSut();

    $httpRequest = new HttpRequest(['name' => $this->module->name]);

    $this->assertEquals(new Ok([
      'id' => $this->module->id,
      'name' => $this->module->name
    ]), $sut->handle($httpRequest));
  }

  public function testShouldReturn500IfDbModuleThrows() { 
    $this->mockThrows();
    $sut = $this->makeSut();

    $httpRequest = new HttpRequest(['name' => $this->faker->name]);

    $httpResponse = $sut->handle($httpRequest);
    $this->assertEquals($httpResponse->statusCode, 500);
  }

  public function testShouldReturn409IfHasConflict() {
    $mock = $this->createMock('App\data\usecases\Module\DbModule');
    $mock->expects($this->once())->method('add')->willThrowException(new DomainError('any_error'));
    $this->dbModule = $mock;

    $sut = $this->makeSut();
    
    $httpRequest = new HttpRequest(['name' => $this->faker->name]);
    $httpResponse = $sut->handle($httpRequest);

    $this->assertEquals($httpResponse->statusCode, 409);
  }
}