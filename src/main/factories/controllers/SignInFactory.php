<?php

namespace App\main\factories\controllers;

use App\data\usecases\Account\DbAccount;
use App\infra\cryptografy\BcryptAdapter;
use App\infra\db\account\MysqlAccountRepository;
use App\presentation\controllers\SignInController;
use App\presentation\interfaces\Controller;
use App\utils\FilterEmailValidator;

class SignInFactory {
  public static function build() : Controller {
    $validatorAdapter = new FilterEmailValidator();
    $accountRepository = new MysqlAccountRepository();
    $encrypterAdapter = new BcryptAdapter();
    $addAccount = new DbAccount($accountRepository, $encrypterAdapter);
    return new SignInController($validatorAdapter, $addAccount);
  }
}