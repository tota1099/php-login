<?php

namespace App\presentation\helpers;

use App\presentation\interfaces\HttpResponse;

class ServerError extends HttpResponse {
  public function __construct()
  {
    parent::__construct(500, [ 'error' => 'Internal server error' ]); 
  }
}