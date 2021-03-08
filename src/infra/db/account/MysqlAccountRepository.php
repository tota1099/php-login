<?php

namespace App\infra\db\account;

use App\data\interfaces\AccountRepository;
use App\domain\errors\DomainError;
use App\domain\model\Account\Account;
use App\domain\model\Account\AddAccountModel;
use App\infra\db\helpers\MysqlHelper;

class MysqlAccountRepository implements AccountRepository {

  public function add(AddAccountModel $addAccountModel) : Account {
    $mysqlHelper = new MysqlHelper();

    if($this->exists('email', $addAccountModel->email)) {
      throw new DomainError('Duplicate entry');
    }

    $sql = "INSERT INTO account (name, email, password, created) VALUES (?,?,?,?)";
    $accountId = $mysqlHelper->insert($sql, [
      $addAccountModel->name,
      $addAccountModel->email,
      $addAccountModel->password,
      (new \DateTime())->format('Y-m-d H:i:s')
    ]);

    return new Account(
      $accountId,
      $addAccountModel->name,
      $addAccountModel->email,
    );
  }

  private function exists(String $field, String $value) : bool {
    $sql = "SELECT COUNT(*) FROM account WHERE {$field} = ? ";
    $mysqlHelper = new MysqlHelper();
    return $mysqlHelper->exists($sql, [ $value ]);
  }
}