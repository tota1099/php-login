<?php

class SignUpFactory {
  public static function build() : Controller {
    $validatorAdapter = new FilterEmailValidator();
    $accountRepository = new MysqlAccountRepository();
    $addAccount = new DbAccount($accountRepository);
    return new SignUpController($validatorAdapter, $addAccount);
  }
}