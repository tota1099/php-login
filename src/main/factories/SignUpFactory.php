<?php

class SignUpFactory {
  public static function build() : Controller {
    $validatorAdapter = new FilterEmailValidator();
    $accountRepository = new MysqlAccountRepository();
    $encrypterAdapter = new BcryptAdapter();
    $addAccount = new DbAccount($accountRepository, $encrypterAdapter);
    return new SignUpController($validatorAdapter, $addAccount);
  }
}