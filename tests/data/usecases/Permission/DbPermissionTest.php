<?php

use App\data\interfaces\PermissionRepository;
use App\data\usecases\Permission\DbPermission;
use App\domain\enums\Permission\PermissionTypes;
use App\domain\model\Account\Account;
use App\domain\model\Module\Module;
use App\domain\model\Permission\Permission;
use App\domain\model\Permission\AddPermissionModel;
use App\domain\model\Tool\Tool;
use PHPUnit\Framework\TestCase;

final class DbPermissionTest extends TestCase
{
  private Faker\Generator $faker;
  private Permission $permission;
  private PermissionRepository $permissionRepository;
  private AddPermissionModel $addPermissionModel;
  public DbPermission $sut;

  protected function setUp() : void
  {
    $this->faker = Faker\Factory::create();
    $account = new Account($this->faker->randomDigit(), $this->faker->name(), $this->faker->email(), $this->faker->date());
    $module = new Module($this->faker->randomDigit(), $this->faker->name());
    $tool = new Tool($this->faker->randomDigit(), $this->faker->name(), $module);

    $this->permission = new Permission($this->faker->randomDigit(), $account, $tool, PermissionTypes::CREATE);
    $this->addPermissionModel = new AddPermissionModel($this->faker->randomDigit(), $tool->id, PermissionTypes::CREATE);
  }

  private function makeSut() : void {
    $this->sut = new DbPermission($this->permissionRepository);
  }

  private function mockPermissionRepositorySuccess() {
    $mock = $this->createMock('App\data\interfaces\PermissionRepository');
    $mock
      ->expects($this->once())
      ->method('add')
      ->with($this->addPermissionModel)
      ->willReturn($this->permission);
    $this->permissionRepository = $mock;
  }

  private function mockPermissionRepositoryThrows() {
    $mock = $this->createMock('App\data\interfaces\PermissionRepository');
    $mock
      ->method('add')
      ->willThrowException(new \Exception('any error permission'));
    $this->permissionRepository = $mock;
  }
  
  public function testShouldCallPermissionRepositoryWithCorrectValues(): void
  {
    $this->mockPermissionRepositorySuccess();
    $this->makeSut();

    $this->sut->add($this->addPermissionModel);
  }

  public function testShouldReturnPermissionOnSuccess(): void
  {
    $this->mockPermissionRepositorySuccess();
    $this->makeSut();

    $this->assertSame($this->permission, $this->sut->add($this->addPermissionModel));
  }

  public function testShouldThrowIfPermissionRepositoryThrows(): void
  {
    $this->mockPermissionRepositoryThrows();

    $this->makeSut();

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('any error permission');
    
    $this->sut->add($this->addPermissionModel);
  }
}