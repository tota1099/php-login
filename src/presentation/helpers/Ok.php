<?php

class Ok extends HttpResponse {
  public function __construct(Array $body)
  {
    parent::__construct(200, $body); 
  }
}