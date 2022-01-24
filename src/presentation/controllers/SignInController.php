<?php

namespace App\presentation\controllers;

use App\data\usecases\Account\DbAccount;
use App\domain\model\Account\AccountExistsByEmailAndPassword;
use App\presentation\errors\InvalidParamError;
use App\presentation\errors\MissingParamError;
use App\presentation\helpers\BadRequest;
use App\presentation\helpers\Ok;
use App\presentation\helpers\ServerError;
use App\presentation\helpers\Unauthorized;
use App\presentation\interfaces\Controller;
use App\presentation\interfaces\EmailValidator;
use App\presentation\interfaces\HttpRequest;
use App\presentation\interfaces\HttpResponse;

class SignInController implements Controller {

  public function __construct(
    private EmailValidator $emailValidator,
    private DbAccount $dbAccount
  ){}

  public function handle(HttpRequest $httpRequest): HttpResponse
  {
    try {
      $body = $httpRequest->body;
      $requiredFields = ['email', 'password'];

      foreach($requiredFields as $fieldName) {
        if(trim(empty($body[$fieldName]))) {
          return new BadRequest(new MissingParamError($fieldName));
        }
      }
  
      if(!$this->emailValidator->isValid($body['email'])) {
        return new BadRequest(new InvalidParamError('email'));
      }

      $account = new AccountExistsByEmailAndPassword($body['email'], $body['password']);

      if (!$this->dbAccount->existsByUserAndPassword($account)) {
        return new Unauthorized();
      }
      
      return new Ok([]);
    } catch(\Exception $e) {
      return new ServerError();
    }
  }
}