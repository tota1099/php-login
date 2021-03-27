<?php

namespace App\presentation\controllers;

use App\data\usecases\Module\DbModule;
use App\data\usecases\Permission\DbPermission;
use App\domain\errors\DomainError;
use App\domain\model\Module\AddModuleModel;
use App\domain\model\Permission\AddPermissionModel;
use App\presentation\errors\MissingParamError;
use App\presentation\helpers\BadRequest;
use App\presentation\helpers\Conflict;
use App\presentation\helpers\Ok;
use App\presentation\helpers\ServerError;
use App\presentation\interfaces\Controller;
use App\presentation\interfaces\HttpRequest;
use App\presentation\interfaces\HttpResponse;

class DeletePermissionController implements Controller {

  public function __construct(
    private DbPermission $dbPermission
  ){}

  public function handle(HttpRequest $httpRequest): HttpResponse
  {
    try {
      $body = $httpRequest->body;
      $requiredFields = ['id'];

      foreach($requiredFields as $fieldName) {
        if(trim(empty($body[$fieldName]))) {
          return new BadRequest(new MissingParamError($fieldName));
        }
      }

      $this->dbPermission->delete($body['id']);
      
      return new Ok([]);
    } catch(DomainError $de) {
      return new Conflict(['error' => $de->getMessage()]);
    } catch(\Exception $e) {
      return new ServerError();
    }
  }
}