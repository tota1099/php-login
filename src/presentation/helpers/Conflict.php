<?php

namespace App\presentation\helpers;

use App\presentation\interfaces\HttpResponse;

class Conflict extends HttpResponse {
  public function __construct(Array $body)
  {
    parent::__construct(409, $body); 
  }
}