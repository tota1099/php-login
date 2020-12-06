
<?php

interface Controller {
  public function handle (HttpRequest $httpRequest) : HttpResponse;
}