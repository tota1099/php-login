<?php

namespace App\data\usecases\Account;

use App\data\interfaces\AccountRepository;
use App\data\interfaces\Encrypter;
use App\domain\model\Account\Account;
use App\domain\model\Account\AccountExistsByEmailAndPassword;
use App\domain\model\Account\AddAccountModel;
use App\domain\usecases\Account\AddAccount;
use App\domain\usecases\Account\AccountExists;

class DbAccount implements AddAccount, AccountExists{
  
  public function __construct(
    private AccountRepository $accountRepository,
    private Encrypter $encrypter
  ) {}

  public function add(AddAccountModel $addAccountModel) : Account {
    $addAccountModel->password = $this->encrypter->encrypt($addAccountModel->password);
    return $this->accountRepository->add($addAccountModel);
  }

  public function existsByUserAndPassword(AccountExistsByEmailAndPassword $account) : bool {
    $accountDb = $this->accountRepository->getByEmail($account->email);
    return $this->encrypter->checkEncrypt($account->password, $accountDb->password);
  }
}