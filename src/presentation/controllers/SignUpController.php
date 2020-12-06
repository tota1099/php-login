<?php

class SignUpController implements Controller {

  public function __construct(
    EmailValidator $emailValidator,
    AccountRepository $accountRepository
  ){}

  public function handle(HttpRequest $httpRequest): HttpResponse
  {
    return new BadRequest(new MissingParamError('name'));    
  }
}