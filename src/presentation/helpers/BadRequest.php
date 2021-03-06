<?php

namespace App\presentation\helpers;

use App\presentation\interfaces\HttpResponse;

class BadRequest extends HttpResponse {
  public function __construct(\Exception $exception)
  {
    parent::__construct(400, [ 'error' => $exception->getMessage() ]); 
  }
}