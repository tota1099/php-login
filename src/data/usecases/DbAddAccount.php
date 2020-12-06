<?php

class DbAddAccount implements AddAccount {
  
  public function __construct(
    private AccountRepository $accountRepository
  ) {}

  public function add(AddAccountModel $addAccountModel) : Account {
    return $this->accountRepository->add($addAccountModel);
  }
}