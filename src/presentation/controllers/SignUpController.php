<?php

class SignUpController implements Controller {

  public function __construct(
    private EmailValidator $emailValidator,
    private AccountRepository $accountRepository
  ){}

  public function handle(HttpRequest $httpRequest): HttpResponse
  {
    try {
      $requiredFields = ['name', 'email', 'password'];
      foreach($requiredFields as $fieldName) {
        if(trim(empty($httpRequest->body[$fieldName]))) {
          return new BadRequest(new MissingParamError($fieldName));
        }
      }
  
      if(!$this->emailValidator->isValid($httpRequest->body['email'])) {
        return new BadRequest(new InvalidParamError('email'));
      }
      return new Ok([]);
    } catch(Exception $e) {
      return new ServerError();
    }
  }
}