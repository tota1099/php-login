<?php

namespace App\presentation\controllers;

use App\data\usecases\Account\DbAccount;
use App\domain\errors\DomainError;
use App\domain\model\Account\AddAccountModel;
use App\presentation\errors\InvalidParamError;
use App\presentation\errors\MissingParamError;
use App\presentation\helpers\BadRequest;
use App\presentation\helpers\Conflict;
use App\presentation\helpers\Ok;
use App\presentation\helpers\ServerError;
use App\presentation\interfaces\Controller;
use App\presentation\interfaces\EmailValidator;
use App\presentation\interfaces\HttpRequest;
use App\presentation\interfaces\HttpResponse;

class SignUpController implements Controller {

  public function __construct(
    private EmailValidator $emailValidator,
    private DbAccount $dbAccount
  ){}

  public function handle(HttpRequest $httpRequest): HttpResponse
  {
    try {
      $body = $httpRequest->body;
      $requiredFields = ['name', 'email', 'password'];

      foreach($requiredFields as $fieldName) {
        if(trim(empty($body[$fieldName]))) {
          return new BadRequest(new MissingParamError($fieldName));
        }
      }
  
      if(!$this->emailValidator->isValid($body['email'])) {
        return new BadRequest(new InvalidParamError('email'));
      }

      $addAccountModel = new AddAccountModel($body['name'], $body['email'], $body['password']);
      $account = $this->dbAccount->add($addAccountModel);
      
      return new Ok([
        'id' => $account->id,
        'name' => $account->name,
        'email' => $account->email,
        'created' => $account->created
      ]);
    } catch(DomainError $de) {
      return new Conflict(['error' => $de->getMessage()]);
    } catch(\Exception $e) {
      return new ServerError();
    }
  }
}