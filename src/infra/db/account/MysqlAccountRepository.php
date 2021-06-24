<?php

namespace App\infra\db\account;

use App\data\interfaces\AccountRepository;
use App\domain\errors\DomainError;
use App\domain\model\Account\Account;
use App\domain\model\Account\AddAccountModel;
use App\infra\db\helpers\MysqlHelper;

class MysqlAccountRepository implements AccountRepository {

  public function get(int $accountId) : Account {
    $mysqlHelper = new MysqlHelper();
    $sql = "SELECT id, name, email, created FROM account WHERE id = ?";

    $account = $mysqlHelper->fetch($sql, [
      $accountId
    ]);

    return new Account(
      $accountId,
      $account['name'],
      $account['email'],
      $account['created']
    );
  }

  public function add(AddAccountModel $addAccountModel) : Account {
    $mysqlHelper = new MysqlHelper();

    if($this->exists('email', $addAccountModel->email)) {
      throw new DomainError('Duplicate entry');
    }

    $sql = "INSERT INTO account (name, email, password, created) VALUES (?,?,?,?)";
    $created = (new \DateTime())->format('Y-m-d H:i:s');

    $accountId = $mysqlHelper->insert($sql, [
      $addAccountModel->name,
      $addAccountModel->email,
      $addAccountModel->password,
      $created
    ]);

    return new Account(
      $accountId,
      $addAccountModel->name,
      $addAccountModel->email,
      $created
    );
  }

  private function exists(String $field, String $value) : bool {
    $sql = "SELECT COUNT(*) FROM account WHERE {$field} = ? ";
    $mysqlHelper = new MysqlHelper();
    return $mysqlHelper->exists($sql, [ $value ]);
  }
}