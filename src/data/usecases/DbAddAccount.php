<?php

class DbAddAccount implements AddAccount {
  
  public function __construct(
    private AddAccountRepository $addAccountRepository
  ) {}

  public function add(AddAccountModel $addAccountModel) : Account {
    return $this->addAccountRepository->add($addAccountModel);
  }
}