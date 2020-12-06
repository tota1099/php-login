<?php

class SignUpController implements Controller {

  public function __construct(
    private EmailValidator $emailValidator,
    private AccountRepository $accountRepository
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
      $this->accountRepository->add($addAccountModel);
      
      return new Ok([]);
    } catch(Exception $e) {
      return new ServerError();
    }
  }
}