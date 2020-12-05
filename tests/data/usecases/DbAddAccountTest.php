<?php

use PHPUnit\Framework\TestCase;

final class DbAddAccountTest extends TestCase
{
  private Faker\Generator $faker;
  private Account $account;
  private AddAccountRepository $addAccountRepository;
  private AddAccountModel $addAccountModel;

  protected function setUp() : void
  {
    $this->faker = Faker\Factory::create();
    $this->account = new Account($this->faker->randomDigit(), $this->faker->name(), $this->faker->email());
    $this->addAccountModel = new AddAccountModel($this->faker->name(), $this->faker->email(), $this->faker->password());
  }

  private function mockSuccess() {
    $mock = $this->createMock('AddAccountRepository');
    $mock->expects($this->once())
        ->method('add')
        ->with($this->addAccountModel)
        ->willReturn($this->account);
    $this->addAccountRepository = $mock;
  }

  private function mockThrows() {
    $mock = $this->createMock('AddAccountRepository');
    $mock->expects($this->once())
        ->method('add')
        ->willThrowException(new Exception('any error'));
    $this->addAccountRepository = $mock;
  }

  public function testShouldCallAddAccountRepositoryWithCorrectValues(): void
  {
    $this->mockSuccess();

    $sut = new DbAddAccount($this->addAccountRepository);

    $sut->add($this->addAccountModel);
  }

  public function testShouldReturnAccountOnSuccess(): void
  {
    $this->mockSuccess();

    $sut = new DbAddAccount($this->addAccountRepository);

    $this->assertSame($this->account, $sut->add($this->addAccountModel));
  }

  public function testShouldThrowIfRepositoryThrows(): void
  {
    $this->mockThrows();

    $sut = new DbAddAccount($this->addAccountRepository);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('any error');
    $sut->add($this->addAccountModel);
  }
}