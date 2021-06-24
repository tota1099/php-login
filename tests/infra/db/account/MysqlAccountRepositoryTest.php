<?php

namespace tests\infra\db\account;

use App\domain\errors\DomainError;
use App\domain\model\Account\AddAccountModel;
use App\infra\db\account\MysqlAccountRepository;
use PHPUnit\Framework\TestCase;

final class MysqlAccountRepositoryTest extends TestCase
{
  public MysqlAccountRepository $sut;
  private \Faker\Generator $faker;

  public function setUp() : void {
    $this->sut = new MysqlAccountRepository();
    $this->faker = \Faker\Factory::create();
  }

  public function testShouldReturnTheAccountRecentlyAdded() {
    $name = $this->faker->name();
    $email = $this->faker->email();
    $password = $this->faker->password();

    $addAccountModel = new AddAccountModel(
      name: $name,
      email: $email,
      password: $password
    );
    $accountAdded = $this->sut->add($addAccountModel);
    
    $account = $this->sut->get($accountAdded->id);

    $this->assertIsInt($account->id);
    $this->assertEquals($name, $account->name);
    $this->assertEquals($email, $account->email);
    $this->assertNotNull($account->created);
  }

  public function testShouldReturnAnAccountOnSuccess() {
    $name = $this->faker->name();
    $email = $this->faker->email();
    $password = $this->faker->password();

    $addAccountModel = new AddAccountModel(
      name: $name,
      email: $email,
      password: $password
    );
    $account = $this->sut->add($addAccountModel);

    $this->assertIsInt($account->id);
    $this->assertEquals($name, $account->name);
    $this->assertEquals($email, $account->email);
  }

  public function testShouldThrowIfDuplicatyEntry() {
    $name = $this->faker->name();
    $email = $this->faker->email();
    $password = $this->faker->password();

    $addAccountModel = new AddAccountModel(
      name: $name,
      email: $email,
      password: $password
    );
    
    $this->expectException(DomainError::class);
    $this->expectExceptionMessage('Duplicate entry');


    $this->sut->add($addAccountModel);
    $this->sut->add($addAccountModel);
  }
}