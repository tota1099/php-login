<?php

use PHPUnit\Framework\TestCase;

final class SignUpControllerTest extends TestCase
{
  private SignUpController $sut;
  private Faker\Generator $faker;

  private function makeEmailValidatorMock() : EmailValidator {
    $mock = $this->createMock('EmailValidator');
    $mock->method('isValid')->willReturn(true);
    return $mock;
  }

  private function makeAccountRepositoryMock(): AccountRepository {
    $mock = $this->createMock('AccountRepository');
    $account = new Account($this->faker->randomDigit(), $this->faker->name(), $this->faker->email());
    $mock->method('add')->willReturn($account);
    return $mock;
  }

  public function setUp() : void {
    $this->faker = Faker\Factory::create();
    $this->sut = new SignUpController(
      $this->makeEmailValidatorMock(),
      $this->makeAccountRepositoryMock()
    );
  }

  public function testShouldReturn400IfNoNameIsProvided() {
    $httpRequest = new HttpRequest([
      'email' => $this->faker->email,
      'password' => $this->faker->password,
    ]);
    $httpResponse = $this->sut->handle($httpRequest);
    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new MissingParamError('name'))->getMessage());
  }

  public function testShouldReturn400IfNoPasswordIsProvided() {
    $httpRequest = new HttpRequest([
      'name' => $this->faker->name,
      'email' => $this->faker->email
    ]);
    $httpResponse = $this->sut->handle($httpRequest);
    $this->assertEquals($httpResponse->statusCode, 400);
    $this->assertEquals($httpResponse->body['error'], (new MissingParamError('password'))->getMessage());
  }
}