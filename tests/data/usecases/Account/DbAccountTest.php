<?php

use data\interfaces\Encrypter;
use PHPUnit\Framework\TestCase;

final class DbAccountTest extends TestCase
{
  private Faker\Generator $faker;
  private Account $account;
  private AccountRepository $accountRepository;
  private Encrypter $encrypter;
  private AddAccountModel $addAccountModel;

  protected function setUp() : void
  {
    $this->faker = Faker\Factory::create();
    $this->account = new Account($this->faker->randomDigit(), $this->faker->name(), $this->faker->email());
    $this->addAccountModel = new AddAccountModel($this->faker->name(), $this->faker->email(), $this->faker->password());
  }

  private function mockAccountRepositorySuccess() {
    $mock = $this->createMock('AccountRepository');
    $mock
      ->method('add')
      ->with($this->addAccountModel)
      ->willReturn($this->account);
    $this->accountRepository = $mock;
  }

  private function mockEncrypterSuccess() {
    $mock = $this->createMock('data\interfaces\Encrypter');
    $mock
      ->expects($this->once())
      ->method('encrypt')
      ->with($this->addAccountModel->password)
      ->willReturn($this->faker->password);
    $this->encrypter = $mock;
  }

  private function mockAccountRepositoryThrows() {
    $mock = $this->createMock('AccountRepository');
    $mock->expects($this->once())
        ->method('add')
        ->willThrowException(new Exception('any error'));
    $this->accountRepository = $mock;
  }

  private function mockEncrypterThrows() {
    $mock = $this->createMock('data\interfaces\Encrypter');
    $mock->expects($this->once())
        ->method('encrypt')
        ->willThrowException(new Exception('any error'));
    $this->encrypter = $mock;
  }

  public function testShouldCallAccountRepositoryAndEncrypterWithCorrectValues(): void
  {
    $this->mockEncrypterSuccess();
    $this->mockAccountRepositorySuccess();

    $sut = new DbAccount($this->accountRepository, $this->encrypter);

    $sut->add($this->addAccountModel);
  }

  public function testShouldReturnAccountOnSuccess(): void
  {
    $this->mockEncrypterSuccess();
    $this->mockAccountRepositorySuccess();

    $sut = new DbAccount($this->accountRepository, $this->encrypter);

    $this->assertSame($this->account, $sut->add($this->addAccountModel));
  }

  public function testShouldThrowIfAccountRepositoryThrows(): void
  {
    $this->mockAccountRepositoryThrows();
    $this->mockEncrypterSuccess();

    $sut = new DbAccount($this->accountRepository, $this->encrypter);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('any error');
    $sut->add($this->addAccountModel);
  }

  public function testShouldThrowIfEncrypterThrows(): void
  {
    $this->mockEncrypterThrows();
    $this->mockAccountRepositorySuccess();

    $sut = new DbAccount($this->accountRepository, $this->encrypter);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('any error');
    $sut->add($this->addAccountModel);
  }
}