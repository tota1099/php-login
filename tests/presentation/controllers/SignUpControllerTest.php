<?php

use PHPUnit\Framework\TestCase;

final class SignUpControllerTest extends TestCase
{
  private EmailValidator $emailValidator;
  private AccountRepository $accountRepository;
  private Faker\Generator $faker;

  private function makeSut(): Controller {
    return new SignUpController($this->emailValidator, $this->accountRepository);
  }

  private function mockSuccess(): void {
    $mock = $this->createMock('EmailValidator');
    $mock->method('isValid')->willReturn(true);
    $this->emailValidator = $mock;

    $mock = $this->createMock('AccountRepository');
    $account = new Account($this->faker->randomDigit(), $this->faker->name(), $this->faker->email());
    $mock->method('add')->willReturn($account);
    $this->accountRepository = $mock;
  }

  public function setUp() : void {
    $this->faker = Faker\Factory::create();
    $this->mockSuccess();
  }

  public function testShouldReturn400IfNoNameIsProvided() {
    $sut = $this->makeSut();
    
    $httpRequest = new HttpRequest([
      'email' => $this->faker->email,
      'password' => $this->faker->password,
    ]);
    $httpResponse = $sut->handle($httpRequest);

    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new MissingParamError('name'))->getMessage());
  }

  public function testShouldReturn400IfNoPasswordIsProvided() {
    $sut = $this->makeSut();

    $httpRequest = new HttpRequest([
      'name' => $this->faker->name,
      'email' => $this->faker->email
    ]);
    $httpResponse = $sut->handle($httpRequest);
    
    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new MissingParamError('password'))->getMessage());
  }

  public function testShouldReturn400IfNoEmailIsProvided() {
    $sut = $this->makeSut();

    $httpRequest = new HttpRequest([
      'name' => $this->faker->name,
      'password' => $this->faker->password
    ]);
    $httpResponse = $sut->handle($httpRequest);
    
    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new MissingParamError('email'))->getMessage());
  }

  public function testShouldCallEmailValidatorWithCorrectEmail() {
    $email = $this->faker->email;
    $mock = $this->createMock('EmailValidator');
    $mock->expects($this->once())->method('isValid')->with($email)->willReturn(true);
    $this->emailValidator = $mock;

    $sut = $this->makeSut();
    
    $httpRequest = new HttpRequest([
      'name' => $this->faker->name,
      'password' => $this->faker->password,
      'email' => $email
    ]);

    $sut->handle($httpRequest);
  }

  public function testShouldReturn400IfEMailIsInvalid() {
    $email = $this->faker->email;
    $mock = $this->createMock('EmailValidator');
    $mock->expects($this->once())->method('isValid')->with($email)->willReturn(false);
    $this->emailValidator = $mock;

    $sut = $this->makeSut();
    
    $httpRequest = new HttpRequest([
      'name' => $this->faker->name,
      'password' => $this->faker->password,
      'email' => $email
    ]);

    $httpResponse = $sut->handle($httpRequest);

    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new InvalidParamError('email'))->getMessage());
  }
}