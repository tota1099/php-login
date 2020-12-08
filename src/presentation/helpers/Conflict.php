<?php

class Conflict extends HttpResponse{
  public function __construct(Array $body)
  {
    parent::__construct(409, $body); 
  }
}