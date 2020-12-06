<?php

use PHPUnit\Framework\TestCase;

final class DbAddAccountTest extends TestCase
{
  private Faker\Generator $faker;
  private Account $account;
  private AccountRepository $accountRepository;
  private AddAccountModel $addAccountModel;

  protected function setUp() : void
  {
    $this->faker = Faker\Factory::create();
    $this->account = new Account($this->faker->randomDigit(), $this->faker->name(), $this->faker->email());
    $this->addAccountModel = new AddAccountModel($this->faker->name(), $this->faker->email(), $this->faker->password());
  }

  private function mockSuccess() {
    $mock = $this->createMock('AccountRepository');
    $mock->expects($this->once())
        ->method('add')
        ->with($this->addAccountModel)
        ->willReturn($this->account);
    $this->accountRepository = $mock;
  }

  private function mockThrows() {
    $mock = $this->createMock('AccountRepository');
    $mock->expects($this->once())
        ->method('add')
        ->willThrowException(new Exception('any error'));
    $this->accountRepository = $mock;
  }

  public function testShouldCallAccountRepositoryWithCorrectValues(): void
  {
    $this->mockSuccess();

    $sut = new DbAddAccount($this->accountRepository);

    $sut->add($this->addAccountModel);
  }

  public function testShouldReturnAccountOnSuccess(): void
  {
    $this->mockSuccess();

    $sut = new DbAddAccount($this->accountRepository);

    $this->assertSame($this->account, $sut->add($this->addAccountModel));
  }

  public function testShouldThrowIfRepositoryThrows(): void
  {
    $this->mockThrows();

    $sut = new DbAddAccount($this->accountRepository);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('any error');
    $sut->add($this->addAccountModel);
  }
}