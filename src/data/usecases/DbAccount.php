<?php

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