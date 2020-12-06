<?php

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
      (new DateTime())->format('Y-m-d H:i:s')
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