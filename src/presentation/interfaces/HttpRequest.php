<?php

namespace App\presentation\interfaces;
class HttpRequest {
  public Array $body;

  public function __construct(Array $body = [])
  {
    $this->body = $body; 
  }
}