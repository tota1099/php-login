<?php

class AddToolController implements Controller {

  public function __construct(
    private DbTool $dbTool
  ){}

  public function handle(HttpRequest $httpRequest): HttpResponse
  {
    try {
      $body = $httpRequest->body;
      $requiredFields = ['name', 'moduleId'];

      foreach($requiredFields as $fieldName) {
        if(trim(empty($body[$fieldName]))) {
          return new BadRequest(new MissingParamError($fieldName));
        }
      }

      $addToolModel = new AddToolModel($body['name'], $body['moduleId']);
      $tool = $this->dbModule->add($addToolModel);
      
      return new Ok([
        'id' => $tool->id,
        'name' => $tool->name,
        'module' => $tool->module
      ]);
    } catch(DomainError $de) {
      return new Conflict(['error' => $de->getMessage()]);
    } catch(Exception $e) {
      return new ServerError();
    }
  }
}