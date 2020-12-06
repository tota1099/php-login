<?php

class HttpRequest {
  public Array $body;

  public function __construct(Array $body = [])
  {
    $this->body = $body; 
  }
}