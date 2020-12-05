<?php

interface AddAccountRepository {
  public function add(AddAccountModel $addAccountModel) : Account;
}