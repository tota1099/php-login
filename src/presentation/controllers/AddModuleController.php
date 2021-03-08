<?php

namespace App\presentation\controllers;

use App\data\usecases\Module\DbModule;
use App\domain\errors\DomainError;
use App\domain\model\Module\AddModuleModel;
use App\presentation\errors\MissingParamError;
use App\presentation\helpers\BadRequest;
use App\presentation\helpers\Conflict;
use App\presentation\helpers\Ok;
use App\presentation\helpers\ServerError;
use App\presentation\interfaces\Controller;
use App\presentation\interfaces\HttpRequest;
use App\presentation\interfaces\HttpResponse;

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
    } catch(\Exception $e) {
      return new ServerError();
    }
  }
}