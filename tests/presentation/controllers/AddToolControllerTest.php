<?php

namespace App\Test\presentation\controllers;

use App\data\usecases\Tool\DbTool;
use App\domain\model\Module\Module;
use App\domain\model\Tool\AddToolModel;
use App\domain\model\Tool\Tool;
use App\presentation\controllers\AddToolController;
use App\presentation\errors\MissingParamError;
use App\presentation\interfaces\Controller;
use App\presentation\interfaces\HttpRequest;
use PHPUnit\Framework\TestCase;

final class AddToolControllerTest extends TestCase
{
  private DbTool $dbTool;
  private Tool $tool;
  private Module $module;
  private \Faker\Generator $faker;

  private function makeSut(): Controller {
    return new AddToolController($this->dbTool);
  }

  private function mockSuccess(): void {
    $mock = $this->createMock('App\data\usecases\Tool\DbTool');
    $this->module = new Module($this->faker->randomDigit(), $this->faker->name());
    $this->tool = new Tool($this->faker->randomDigit(), $this->faker->name(), $this->module);
    $mock->method('add')->with(new AddToolModel($this->tool->name, $this->tool->module->id))->willReturn($this->tool);
    $this->dbTool = $mock;
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

  public function testShouldReturn400IfNoModuleIsProvided() {  
    $this->mockSuccess();
    $sut = $this->makeSut();

    $httpRequest = new HttpRequest(['name' => $this->tool->name]);
    $httpResponse = $sut->handle($httpRequest);

    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new MissingParamError('moduleId'))->getMessage());
  }
}