<?php

class HttpResponse {
  public int $statusCode;
  public Array $body;

  public function __construct(int $statusCode, Array $body = [])
  {
    $this->statusCode = $statusCode;
    $this->body = $body; 
  }
}