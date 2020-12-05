<?php

interface AddAccount {
  public function add(AddAccountModel $accountModel): Account;
}