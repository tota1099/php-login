<?php

interface AccountRepository {
  public function add(AddAccountModel $addAccountModel) : Account;
}