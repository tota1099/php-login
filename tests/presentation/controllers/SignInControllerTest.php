<?php

namespace App\Test\presentation\controllers;

use App\data\usecases\Account\DbAccount;
use App\domain\errors\DomainError;
use App\presentation\controllers\SignInController;
use App\presentation\errors\InvalidParamError;
use App\presentation\errors\MissingParamError;
use App\presentation\interfaces\Controller;
use App\presentation\interfaces\EmailValidator;
use App\presentation\interfaces\HttpRequest;
use PHPUnit\Framework\TestCase;

final class SignInControllerTest extends TestCase
{
  private EmailValidator $emailValidator;
  private DbAccount $dbAccount;
  private \Faker\Generator $faker;

  private function makeSut(): Controller {
    return new SignInController($this->emailValidator, $this->dbAccount);
  }

  private function mockSuccess(): void {
    $mock = $this->createMock('App\presentation\interfaces\EmailValidator');
    $mock->method('isValid')->willReturn(true);
    $this->emailValidator = $mock;

    $mock = $this->createMock('App\data\usecases\Account\DbAccount');
    $mock->method('existsByUserAndPassword')->willReturn(true);
    $this->dbAccount = $mock;
  }

  public function setUp() : void {
    $this->faker = \Faker\Factory::create();
    $this->mockSuccess();
  }

  public function testShouldReturn400IfNoEmailIsProvided() {
    $sut = $this->makeSut();
    
    $httpRequest = new HttpRequest([
      'password' => $this->faker->password,
    ]);
    $httpResponse = $sut->handle($httpRequest);

    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new MissingParamError('email'))->getMessage());
  }

  public function testShouldReturn400IfNoPasswordIsProvided() {
    $sut = $this->makeSut();

    $httpRequest = new HttpRequest([
      'email' => $this->faker->email
    ]);
    $httpResponse = $sut->handle($httpRequest);
    
    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new MissingParamError('password'))->getMessage());
  }

  public function testShouldCallEmailValidatorWithCorrectEmail() {
    $email = $this->faker->email;
    $mock = $this->createMock('App\presentation\interfaces\EmailValidator');
    $mock->expects($this->once())->method('isValid')->with($email)->willReturn(true);
    $this->emailValidator = $mock;

    $sut = $this->makeSut();
    
    $httpRequest = new HttpRequest([
      'password' => $this->faker->password,
      'email' => $email
    ]);

    $sut->handle($httpRequest);
  }

  public function testShouldReturn400IfEMailIsInvalid() {
    $email = $this->faker->email;
    $mock = $this->createMock('App\presentation\interfaces\EmailValidator');
    $mock->expects($this->once())->method('isValid')->with($email)->willReturn(false);
    $this->emailValidator = $mock;

    $sut = $this->makeSut();
    
    $httpRequest = new HttpRequest([
      'password' => $this->faker->password,
      'email' => $email
    ]);

    $httpResponse = $sut->handle($httpRequest);

    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new InvalidParamError('email'))->getMessage());
  }

  public function testShouldReturn500IfEmailValidThrows() {
    $mock = $this->createMock('App\presentation\interfaces\EmailValidator');
    $mock->expects($this->once())->method('isValid')->willThrowException(new \Exception());
    $this->emailValidator = $mock;

    $sut = $this->makeSut();
    
    $httpRequest = new HttpRequest([
      'password' => $this->faker->password,
      'email' => $this->faker->email
    ]);

    $httpResponse = $sut->handle($httpRequest);
    $this->assertEquals($httpResponse->statusCode, 500);
  }

  public function testShouldReturn401IfEmailAndPasswordIsInvalid() {
    $mock = $this->createMock('App\data\usecases\Account\DbAccount');
    $mock->method('existsByUserAndPassword')->willReturn(false);
    $this->dbAccount = $mock;
    
    $sut = $this->makeSut();
    
    $httpRequest = new HttpRequest([
      'email' => $this->faker->email,
      'password' => $this->faker->password
    ]);

    $httpResponse = $sut->handle($httpRequest);
    $this->assertEquals($httpResponse->statusCode, 401);
  }

  public function testShouldReturn200IfEmailAndPasswordIsValid() {
    $sut = $this->makeSut();
    
    $httpRequest = new HttpRequest([
      'email' => $this->faker->email,
      'password' => $this->faker->password
    ]);

    $httpResponse = $sut->handle($httpRequest);
    $this->assertEquals($httpResponse->statusCode, 200);
  }
}