<?php

namespace App\data\usecases\Account;

use App\data\interfaces\AccountRepository;
use App\data\interfaces\Encrypter;
use App\domain\model\Account\Account;
use App\domain\model\Account\AddAccountModel;
use App\domain\usecases\Account\AddAccount;

class DbAccount implements AddAccount {
  
  public function __construct(
    private AccountRepository $accountRepository,
    private Encrypter $encrypter
  ) {}

  public function add(AddAccountModel $addAccountModel) : Account {
    $addAccountModel->password = $this->encrypter->encrypt($addAccountModel->password);
    return $this->accountRepository->add($addAccountModel);
  }
}