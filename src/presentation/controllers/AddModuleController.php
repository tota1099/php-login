<?php

class AddModuleController implements Controller {

  public function __construct(
    private DbModule $dbModule
  ){}

  public function handle(HttpRequest $httpRequest): HttpResponse
  {
    try {
      $body = $httpRequest->body;

      if(trim(empty($body['name']))) {
        return new BadRequest(new MissingParamError('name'));
      }

      $addModuleModel = new AddModuleModel($body['name']);
      $module = $this->dbModule->add($addModuleModel);
      
      return new Ok([
        'id' => $module->id,
        'name' => $module->name
      ]);
    } catch(DomainError $de) {
      return new Conflict(['error' => $de->getMessage()]);
    } catch(Exception $e) {
      return new ServerError();
    }
  }
}