<?php

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
        'email' => $account->email
      ]);
    } catch(DomainError $de) {
      return new Conflict(['error' => $de->getMessage()]);
    } catch(Exception $e) {
      return new ServerError();
    }
  }
}