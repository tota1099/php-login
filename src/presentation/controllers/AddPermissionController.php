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

class AddPermissionController implements Controller {

  public function __construct(
    private DbPermission $dbPermission
  ){}

  public function handle(HttpRequest $httpRequest): HttpResponse
  {
    try {
      $body = $httpRequest->body;
      $requiredFields = ['accountId', 'toolId', 'type'];

      foreach($requiredFields as $fieldName) {
        if(trim(empty($body[$fieldName]))) {
          return new BadRequest(new MissingParamError($fieldName));
        }
      }

      $addPermissionModel = new AddPermissionModel($body['accountId'], $body['toolId'], $body['type']);
      $permission = $this->dbPermission->add($addPermissionModel);
      
      return new Ok([
        'id' => $permission->id,
        'account' => $permission->account,
        'module' => $permission->tool,
        'type' => $permission->type
      ]);
    } catch(DomainError $de) {
      return new Conflict(['error' => $de->getMessage()]);
    } catch(\Exception $e) {
      echo '<pre>'; print_r($e); echo '</pre>';
      return new ServerError();
    }
  }
}