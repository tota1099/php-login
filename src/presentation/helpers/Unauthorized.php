<?php

namespace App\presentation\helpers;

use App\presentation\interfaces\HttpResponse;

class Unauthorized extends HttpResponse {
  public function __construct()
  {
    parent::__construct(401); 
  }
}