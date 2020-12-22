<?php

interface AddTool {
  public function add(AddToolModel $toolModel): Tool;
}