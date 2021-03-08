<?php

namespace App\presentation\interfaces;

interface Controller {
  public function handle (HttpRequest $httpRequest) : HttpResponse;
}