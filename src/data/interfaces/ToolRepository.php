<?php

interface ToolRepository {
  public function add(AddToolModel $addToolModel) : Tool;
}