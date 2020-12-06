<?php

use PHPUnit\Framework\TestCase;

final class MysqlAccountRepositoryTest extends TestCase
{
  public MysqlAccountRepository $sut;
  private Faker\Generator $faker;

  public function setUp() : void {
    $this->sut = new MysqlAccountRepository();
    $this->faker = Faker\Factory::create();
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
}