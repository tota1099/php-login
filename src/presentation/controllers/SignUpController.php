<?php

class SignUpController implements Controller {

  public function __construct(
    EmailValidator $emailValidator,
    AccountRepository $accountRepository
  ){}

  public function handle(HttpRequest $httpRequest): HttpResponse
  {
    $requiredFields = ['name', 'email', 'password'];
    foreach($requiredFields as $fieldName) {
      if(trim(empty($httpRequest->body[$fieldName]))) {
        return new BadRequest(new MissingParamError($fieldName));
      }
    }    
  }
}