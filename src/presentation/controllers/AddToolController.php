<?php

namespace App\presentation\controllers;

use App\data\usecases\Tool\DbTool;
use App\domain\errors\DomainError;
use App\domain\model\Tool\AddToolModel;
use App\presentation\errors\MissingParamError;
use App\presentation\helpers\BadRequest;
use App\presentation\helpers\Conflict;
use App\presentation\helpers\Ok;
use App\presentation\helpers\ServerError;
use App\presentation\interfaces\Controller;
use App\presentation\interfaces\HttpRequest;
use App\presentation\interfaces\HttpResponse;

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
      $tool = $this->dbTool->add($addToolModel);
      
      return new Ok([
        'id' => $tool->id,
        'name' => $tool->name,
        'module' => $tool->module
      ]);
    } catch(DomainError $de) {
      return new Conflict(['error' => $de->getMessage()]);
    } catch(\Exception $e) {
      return new ServerError();
    }
  }
}